<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\UseCase\Feed\CreateFeed\CreateFeedEntryDto;
use App\UseCase\Feed\CreateFeed\CreateFeedHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Throwable;

#[AsController]
class CreateFeedController
{
    #[Route('/post/create', name: 'create post', methods: Request::METHOD_POST, format: 'json')]
    public function handle(
        #[CurrentUser] ?User $user,
        #[MapRequestPayload(validationFailedStatusCode: Response::HTTP_BAD_REQUEST)]
        CreateFeedEntryDto $entryDto,
        CreateFeedHandler $handler,
    ): Response {
        try {
            $handler->handle($user, $entryDto);
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

        return new JsonResponse();
    }
}
