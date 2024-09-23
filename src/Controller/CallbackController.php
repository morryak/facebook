<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class CallbackController
{
    #[Route('/callback', methods: [Request::METHOD_GET], format: 'json')]
    public function handle(): Response
    {
        dd('Silviya');
    }
}
