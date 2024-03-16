<?php

declare(strict_types=1);

namespace App\AppBundle\Service;

use Exception;

use function bin2hex;
use function random_bytes;

class TokenGeneratorService
{
    private const TOKEN_BYTES_COUNT = 64;

    /**
     * @throws Exception
     */
    public function generateToken(): string
    {
        return bin2hex(random_bytes(self::TOKEN_BYTES_COUNT));
    }
}
