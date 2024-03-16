<?php

declare(strict_types=1);

namespace App\UseCase;

use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;

abstract class AbstractManager
{
    public function __construct(private readonly ManagerRegistry $doctrine)
    {
    }

    /**
     * @return Connection
     */
    public function getConnection(): object
    {
        return $this->doctrine->getConnection();
    }
}
