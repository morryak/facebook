<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\UseCase\Feed\GetFeed\GetFeedHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Throwable;

#[AsController]
class GetFeedController
{
    #[Route('/post/feed', name: 'get feed list', methods: Request::METHOD_GET, format: 'json')]
    public function handle(
        #[CurrentUser] ?User $user,
        GetFeedHandler $handler,
        #[MapQueryParameter(filter: FILTER_VALIDATE_INT)] int $limit = 1000,
        #[MapQueryParameter(filter: FILTER_VALIDATE_INT)] int $offset = 0,
    ): Response {
        try {
            $result = $handler->handle($user, $limit, $offset);
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
