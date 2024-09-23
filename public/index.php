<?php

declare(strict_types=1);

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;

require_once \dirname(__DIR__) . '/vendor/autoload_runtime.php';

(new Dotenv())->bootEnv(\dirname(__DIR__) . '/.env');

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
