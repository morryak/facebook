<?php

declare(strict_types=1);

namespace App\Controller;

use App\AppBundle\Exception\UserNotFoundException;
use App\Entity\User;
use App\UseCase\User\AddUserFriend\AddUserFriendHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Throwable;

#[AsController]
class AddUserFriendController
{
    #[Route('/friend/set/{user_id}', methods: [Request::METHOD_PUT], format: 'json')]
    public function handle(#[CurrentUser] ?User $user, string $user_id, AddUserFriendHandler $handler): Response
    {
        try {
            $handler->handle($user, $user_id);
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

        return new JsonResponse();
    }
}
