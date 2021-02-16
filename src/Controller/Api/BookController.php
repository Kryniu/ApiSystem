<?php


namespace App\Controller\Api;


use App\Service\Api\Book\BookService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/book")
 */
class BookController
{

    /**
     * @Route("/add", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Returns success message",
     *     @OA\JsonContent(
     *          @OA\Property(type="string", property="message")
     *     )
     * )
     * @OA\Response(
     *     response=400,
     *     description="Returns error message",
     *     @OA\JsonContent(
     *          @OA\Property(type="string", property="message")
     *     )
     * )
     * @OA\Parameter(
     *     name="title",
     *     in="query",
     *     required=true,
     *     description="book title",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="description",
     *     in="query",
     *     description="book description (html)",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="shortDescription",
     *     in="query",
     *     description="book short description",
     *     @OA\Schema(type="string")
     * )
     */
    public function add(Request $request, BookService $bookService): JsonResponse
    {
        try {
            $bookService->add($request);

            return $bookService->getSuccessResponse('Added book');
        } catch (\Throwable $exception) {
            return $bookService->errorResponse($exception);
        }
    }

    /**
     * @Route("/update/{id}", requirements={"id"="\d+"}, methods={"PUT"})
     * @OA\Response(
     *     response=200,
     *     description="Returns success message",
     *     @OA\JsonContent(
     *          @OA\Property(type="string", property="message")
     *     )
     * )
     * @OA\Response(
     *     response=400,
     *     description="Returns error message",
     *     @OA\JsonContent(
     *          @OA\Property(type="string", property="message")
     *     )
     * )
     * @OA\Parameter(
     *     name="title",
     *     in="query",
     *     description="book title",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="description",
     *     in="query",
     *     description="book description (html)",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="shortDescription",
     *     in="query",
     *     description="book short description",
     *     @OA\Schema(type="string")
     * )
     */
    public function update(int $id, Request $request, BookService $bookService): JsonResponse
    {
        try {
            $bookService->update($request, $id);

            return $bookService->getSuccessResponse('Updated user id: ' . $id);
        } catch (\Throwable $exception) {
            return $bookService->errorResponse($exception);
        }
    }

    /**
     * @Route("/delete/{id}", requirements={"id"="\d+"}, methods={"DELETE"})
     * @OA\Response(
     *     response=200,
     *     description="Returns success message",
     *     @OA\JsonContent(
     *          @OA\Property(type="string", property="message")
     *     )
     * )
     * @OA\Response(
     *     response=400,
     *     description="Returns error message",
     *     @OA\JsonContent(
     *          @OA\Property(type="string", property="message")
     *     )
     * )
     */
    public function delete(int $id, BookService $bookService): JsonResponse
    {
        try {
            $bookService->delete($id);

            return $bookService->getSuccessResponse('Deleted user id: ' . $id);
        } catch (\Throwable $exception) {
            return $bookService->errorResponse($exception);
        }
    }

    /**
     * @Route("/userBook/{userId}", requirements={"userId"="\d+"}, methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns success message",
     *     @OA\JsonContent(
     *          @OA\Property(type="string", property="title"),
     *          @OA\Property(type="string", property="description", format="html"),
     *          @OA\Property(type="string", property="shortDescription")
     *     )
     * )
     * @OA\Response(
     *     response=400,
     *     description="Returns error message",
     *     @OA\JsonContent(
     *          @OA\Property(type="string", property="message")
     *     )
     * )
     */
    public function userBooks(int $userId, BookService $bookService): JsonResponse
    {
        try {
            return $bookService->getUserBooks($userId);
        } catch (\Throwable $exception) {
            return $bookService->errorResponse($exception);
        }
    }

    /**
     * @Route("/assign_user_book", methods={"PUT"})
     * @OA\Response(
     *     response=200,
     *     description="Returns success message",
     *     @OA\JsonContent(
     *          @OA\Property(type="string", property="message")
     *     )
     * )
     * @OA\Response(
     *     response=400,
     *     description="Returns error message",
     *     @OA\JsonContent(
     *          @OA\Property(type="string", property="message")
     *     )
     * )
     * @OA\Parameter(
     *     name="userId",
     *     in="query",
     *     description="user id",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="booksId",
     *     in="query",
     *     required=true,
     *     description="books id",
     *     @OA\Schema(
     *           type="array",
     *           @OA\Items(type="integer", description="book id"),
     *         ),
     * )
     */
    public function assignUserBook(Request $request, BookService $bookService): JsonResponse
    {
        try {
            $bookService->assignUserBook($request);

            return $bookService->getSuccessResponse('Assigned user books');
        } catch (\Throwable $exception) {
            return $bookService->errorResponse($exception);
        }
    }

    /**
     * @Route("/search_book", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns success message ",
     *     @OA\JsonContent(
     *          @OA\Property(type="string", property="title"),
     *          @OA\Property(type="string", property="description", format="html"),
     *          @OA\Property(type="string", property="shortDescription")
     *     )
     * )
     * @OA\Response(
     *     response=400,
     *     description="Returns error message",
     *     @OA\JsonContent(
     *          @OA\Property(type="string", property="message")
     *     )
     * )
     * @OA\Parameter(
     *     name="search",
     *     in="query",
     *     description="search",
     *     @OA\Schema(type="string")
     * )
     */
    public function searchBook(Request $request, BookService $bookService): JsonResponse
    {
        try {
            return $bookService->searchBook($request);
        } catch (\Throwable $exception) {
            return $bookService->errorResponse($exception);
        }
    }
}