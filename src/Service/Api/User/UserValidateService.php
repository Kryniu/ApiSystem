<?php


namespace App\Service\Api\User;


use App\Entity\User;
use App\Exception\User\EmailAlreadyExistException;
use App\Exception\User\NotFoundIdException;
use App\Exception\ValidatorException;
use App\Repository\UserRepository;
use App\Util\Api;
use App\Validator\Api\UserEmailValidator;
use App\Validator\Api\UserValidator;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserValidateService
{
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(ValidatorInterface $validator, UserRepository $userRepository)
    {
        $this->validator = $validator;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws Exception
     */
    public function add(Request $request): void
    {
        $email = $request->get('email');
        $userValidator = new UserValidator(
            $request->get('firstName'),
            $request->get('lastName'),
            $email,
            $request->get('slug'),
        );
        $errors = $this->validator->validate($userValidator);
        if ($errors->count() > 0) {
            throw new ValidatorException($errors);
        }
        $userFoundByEmail = $this->userRepository->findByEmail($email);
        if (null !== $userFoundByEmail) {
            throw new EmailAlreadyExistException($email);
        }
    }

    /**
     * @throws Exception
     */
    public function update(Request $request, int $userId): void
    {
        $email = $request->get('email');
        if (empty($email)) {
            return;
        }
        $userEmailValidator = new UserEmailValidator($email);
        $errors = $this->validator->validate($userEmailValidator);
        if ($errors->count() > 0) {
            throw new ValidatorException($errors);
        }
        $userFoundByEmail = $this->userRepository->findByEmail($email, $userId);
        if (null !== $userFoundByEmail) {
            throw new EmailAlreadyExistException($email);
        }
    }

}