<?php

declare(strict_types=1);

namespace App\UseCase\User\LoginUser;


class LoginHandler
{
    public function __construct(private TokenGeneratorService $tokenGenerator)
    {
    }
}