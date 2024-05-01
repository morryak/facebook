<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\UseCase\User\LoginUser\InvalidCredentialsException;
use App\UseCase\User\LoginUser\LoginHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class LoginController extends AbstractController
{
    //json_login
    #[Route('/login', name: 'login', methods: Request::METHOD_POST)]
    public function index(
        #[CurrentUser] ?User $user,
        LoginHandler $handler,
    ): Response {
        try {
            $resultDto = $handler->handle($user);
        } catch (InvalidCredentialsException $e) {
            return $this->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json($resultDto);
    }
}
