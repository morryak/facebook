<?php

declare(strict_types=1);

namespace App\Controller;

use App\UseCase\User\GetUser\GetUserHandler;
use App\UseCase\User\GetUser\UserNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[AsController]
class GetUserController
{
    #[Route('/user/get/{id}', name: 'get user', requirements: ['id' => "\d+"], methods: Request::METHOD_GET, format: 'json')]
    public function handle(
        int $id,
        GetUserHandler $handler
    ): Response {
        try {
            $result = $handler->handle($id);
        } catch (UserNotFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return new JsonResponse(
                [
                    'message' => $e->getMessage(),
                    'request_id' => 'request_id',
                    'code' => 0,
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR,
                [
                    'Retry-After' => 10,
                ]
            );
        }

        return new JsonResponse($result, Response::HTTP_OK);
    }
}
