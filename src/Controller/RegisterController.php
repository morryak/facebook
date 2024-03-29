<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\RequestDto\RegistrationEntryDto;
use App\UseCase\User\Registration\RegistrationHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[AsController]
class RegisterController
{
    #[Route('/user/register', name: 'registration', methods: Request::METHOD_POST, format: 'json')]
    public function handle(
        #[MapRequestPayload(validationFailedStatusCode: Response::HTTP_BAD_REQUEST)]
        RegistrationEntryDto $entryDto,
        RegistrationHandler $handler
    ): Response {
        try {
            $userId = $handler->handle($entryDto);
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

        return new JsonResponse(['user_id' => $userId], Response::HTTP_OK);
    }
}
