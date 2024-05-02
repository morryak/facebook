<?php

declare(strict_types=1);

namespace App\AppBundle\Manager;

use Predis\Client as RedisClient;

readonly class RedisManager
{
    public function __construct(
        private RedisClient $redisClient
    ) {
    }

    public function getConnection(): RedisClient
    {
        return $this->redisClient;
    }
}
