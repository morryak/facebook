<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\RequestDto\RegistrationEntryDto;
use App\UseCase\Registration\RegistrationHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

use function dd;

#[AsController]
class RegisterController
{
    // map request payload
    #[Route('/registration', name: 'registration', methods: 'POST')]
    public function handle(
        #[MapRequestPayload]
        RegistrationEntryDto $entryDto,
        RegistrationHandler $handler
    ): Response {
        dd($entryDto);
//        dd($request);

        $handler->handle($entryDto);
        dd('exit');
        return new Response(
            '<html><body>Lucky number: ' . $number . '</body></html>'
        );
    }
}
