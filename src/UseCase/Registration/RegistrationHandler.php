<?php

declare(strict_types=1);

namespace App\UseCase\Registration;

use App\DTO\RequestDto\RegistrationEntryDto;

class RegistrationHandler
{
    public function __construct(RegistrationManager $manager)
    {
    }

    public function handle(RegistrationEntryDto $entryDto)
    {
        dd('lol', $entryDto);
    }
}