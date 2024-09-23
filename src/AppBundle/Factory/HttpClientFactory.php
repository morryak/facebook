<?php

declare(strict_types=1);

namespace App\AppBundle\Factory;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;

class HttpClientFactory
{
    public static function createGuzzleClient(): GuzzleClient
    {
        $config['handler'] = HandlerStack::create();

        return new GuzzleClient($config);
    }
}
