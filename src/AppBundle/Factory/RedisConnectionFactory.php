<?php

declare(strict_types=1);

namespace App\AppBundle\Factory;

use Predis\Client as RedisClient;

class RedisConnectionFactory
{
    public static function createRedisClient(string $redisUrl): RedisClient
    {
        return new RedisClient($redisUrl . '?read_write_timeout=0');
    }
}
