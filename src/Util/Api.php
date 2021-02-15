<?php


namespace App\Util;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Api
{
    public function errorResponse(\Throwable $exception): JsonResponse
    {
        return new JsonResponse([
            'message' => $exception->getMessage(),
        ], Response::HTTP_BAD_REQUEST);
    }

    public function getSuccessResponse(string $message): JsonResponse
    {
        return new JsonResponse([
            'message' => $message
        ], Response::HTTP_OK);
    }
}