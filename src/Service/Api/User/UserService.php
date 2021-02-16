<?php


namespace App\Service\Api\User;


use App\Entity\User;
use App\Exception\User\NotFoundIdException;
use App\Repository\UserRepository;
use App\Util\Api;
use App\Util\Sms\SmsNotificator;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

class UserService extends Api
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private UserValidateService $userValidateService,
        private SmsNotificator $smsNotificator,
        private TranslatorInterface $translator,
        private LoggerInterface $logger)
    {
    }

    /**
     * @throws Exception
     */
    public function add(Request $request): void
    {
        $this->userValidateService->add($request);
        $user = new User();
        $user->setFirstName($request->get('firstName'))
            ->setLastName($request->get('lastName'))
            ->setEmail($request->get('email'))
            ->setSlug($request->get('slug'));
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @throws NotFoundIdException
     */
    public function update(Request $request, int $id): void
    {
        $user = $this->userRepository->find($id);
        if (null === $user) {
            throw new NotFoundIdException($id);
        }
        $this->userValidateService->update($request, $id);
        $update = false;
        if ($request->get('firstName') !== '') {
            $user->setFirstName($request->get('firstName'));
            $update = true;
        }
        if ($request->get('lastName') !== '') {
            $user->setLastName($request->get('lastName'));
            $update = true;
        }
        if ($request->get('email') !== '') {
            $user->setEmail($request->get('email'));
            $update = true;
        }
        if ($request->get('slug') !== '') {
            $user->setSlug($request->get('slug'));
            $update = true;
        }
        if ($update) {
            $this->entityManager->flush();
        }
    }

    /**
     * @throws NotFoundIdException
     */
    public function delete(int $id): void
    {
        $user = $this->userRepository->find($id);
        if (null === $user) {
            throw new NotFoundIdException($id);
        }
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        try {
            $this->smsNotificator->send($_ENV['ADMINISTRATION_PHONE_NUMBER'], $this->translator->trans('api.user.sms_notification.account_deleted'));
        } catch (Throwable $exception) {
            $this->logger->alert('User delete send sms', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString()
            ]);
        }
    }
}