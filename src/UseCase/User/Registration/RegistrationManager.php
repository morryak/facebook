<?php

declare(strict_types=1);

namespace App\UseCase\User\Registration;

use App\UseCase\AbstractManager;
use Doctrine\DBAL\Exception as DbalException;

class RegistrationManager extends AbstractManager
{
    /**
     * @throws DbalException
     */
    public function insertUser(array $user): string
    {
        return (string) $this->getConnection()->insert('user', $user);
    }
}
