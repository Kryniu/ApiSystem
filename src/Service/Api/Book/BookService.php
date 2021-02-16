<?php


namespace App\Service\Api\Book;


use App\Entity\Book;
use App\Exception\Book\EmptyTitleException;
use App\Exception\Book\NotFoundIdException as BookNotFoundIdException;
use App\Exception\User\NotFoundIdException as UserNotFoundIdException;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use App\Util\Api;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BookService extends Api
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private BookRepository $bookRepository,
        private UserRepository $userRepository,
        private LoggerInterface $logger
    )
    {
    }

    /**
     * @throws EmptyTitleException
     */
    public function add(Request $request): void
    {
        $title = $request->get('title');
        if (empty($title)) {
            throw new EmptyTitleException();
        }
        $book = new Book();
        $book->setTitle($title)
            ->setDescription($request->get('description'))
            ->setShortDescription($request->get('shortDescription'));
        $this->entityManager->persist($book);
        $this->entityManager->flush();
    }

    /**
     * @throws BookNotFoundIdException
     */
    public function update(Request $request, int $id): void
    {
        $book = $this->bookRepository->find($id);
        if (null === $book) {
            throw new BookNotFoundIdException($id);
        }
        $update = false;
        if ($request->get('title') !== '') {
            $book->setTitle($request->get('title'));
            $update = true;
        }
        if ($request->get('description') !== '') {
            $book->setDescription($request->get('description'));
            $update = true;
        }
        if ($request->get('shortDescription') !== '') {
            $book->setShortDescription($request->get('shortDescription'));
            $update = true;
        }
        if ($update) {
            $this->entityManager->flush();
        }
    }

    /**
     * @throws BookNotFoundIdException
     */
    public function delete(int $id): void
    {
        $book = $this->bookRepository->find($id);
        if (null === $book) {
            throw new BookNotFoundIdException($id);
        }
        $this->entityManager->remove($book);
        $this->entityManager->flush();
    }

    public function getUserBooks(int $userId): JsonResponse
    {
        $books = [];
        foreach ($this->bookRepository->findByUser($userId) as $book) {
            $books[] = $book->toArray();
        }

        return new JsonResponse($books, Response::HTTP_OK);
    }

    /**
     * @throws UserNotFoundIdException
     */
    public function assignUserBook(Request $request): void
    {
        $userId = (int)$request->get('userId', '');
        $user = $this->userRepository->find($userId);
        if (null === $user) {
            throw new UserNotFoundIdException($userId);
        }
        $booksId = json_decode($request->get('booksId'), true);
        if (!is_array($booksId)) {
            throw new \Exception('Parameter booksId is not array');
        }
        foreach ($booksId as $bookId) {
            $book = $this->bookRepository->find((int)$bookId);
            if (null === $book) {
                $this->logger->warning('Assign user book - book id not found', [
                    'bookId' => $bookId,
                ]);
                continue;
            }
            $user->addBook($book);
        }
        $this->entityManager->flush();
    }

    public function searchBook(Request $request): JsonResponse
    {
        $books = [];
        foreach ($this->bookRepository->findValue($request->get('search', '')) as $book) {
            $books[] = $book->toArray();
        }

        return new JsonResponse($books, Response::HTTP_OK);
    }
}