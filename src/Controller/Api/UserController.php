<?php


namespace App\Controller\Api;


use App\Service\Api\User\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @Route("/api/user")
 */
class UserController
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
     *     name="firstName",
     *     in="query",
     *     description="user first name",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="lastName",
     *     in="query",
     *     description="user last name",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="email",
     *     in="query",
     *     description="user email",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="slug",
     *     in="query",
     *     description="user slug",
     *     @OA\Schema(type="string")
     * )
     */
    public function add(Request $request, UserService $userService): JsonResponse
    {
        try {
            $userService->add($request);

            return $userService->getSuccessResponse('Added user');
        } catch (\Throwable $exception) {
            return $userService->errorResponse($exception);
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
     *     name="firstName",
     *     in="query",
     *     description="user first name",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="lastName",
     *     in="query",
     *     description="user last name",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="email",
     *     in="query",
     *     description="user email",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="slug",
     *     in="query",
     *     description="user slug",
     *     @OA\Schema(type="string")
     * )
     */
    public function update(int $id, Request $request, UserService $userService): JsonResponse
    {
        try {
            $userService->update($request, $id);

            return $userService->getSuccessResponse('Updated user id: '. $id);
        } catch (\Throwable $exception) {
            return $userService->errorResponse($exception);
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
    public function delete(int $id, UserService $userService): JsonResponse
    {
        try {
            $userService->delete($id);

            return $userService->getSuccessResponse('Deleted user id: '. $id);
        } catch (\Throwable $exception) {
            return $userService->errorResponse($exception);
        }
    }
}