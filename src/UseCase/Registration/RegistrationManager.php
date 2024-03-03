<?php

declare(strict_types=1);

namespace App\UseCase\Registration;

use Symfony\Bridge\Doctrine\ManagerRegistry;

class RegistrationManager
{
    public function __construct(ManagerRegistry $doctrine)
    {
    }
}