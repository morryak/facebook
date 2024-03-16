<?php

declare(strict_types=1);

namespace App\DTO\ResponseDto;

class LoginResultDto
{
    public function __construct(public string $token)
    {
    }
}
