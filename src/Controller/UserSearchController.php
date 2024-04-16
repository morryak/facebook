<?php

declare(strict_types=1);

namespace App\Controller;

use App\UseCase\User\GetUser\UserNotFoundException;
use App\UseCase\User\UserSearch\UserSearchHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[AsController]
class UserSearchController
{
    #[Route('/user/search', name: 'get user', methods: Request::METHOD_GET, format: 'json')]
    public function handle(
        #[MapQueryParameter] string $first_name,
        #[MapQueryParameter] string $last_name,
        UserSearchHandler $handler
    ) {
        try {
            $result = $handler->handle($first_name, $last_name);
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
