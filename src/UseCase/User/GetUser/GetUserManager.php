<?php

declare(strict_types=1);

namespace App\UseCase\User\GetUser;

use App\UseCase\AbstractManager;
use Doctrine\DBAL\Exception as DbalException;

class GetUserManager extends AbstractManager
{
    /**
     * @throws DbalException
     */
    public function getUser(int $id): array
    {
        $sql = '
            SELECT 
                u.name,
                u.email,
                u.last_name,
                u.sex,
                u.city,
                u.birth_date,
                u.biography
            FROM user u
            WHERE u.id = :id
        ';

        $result = $this->getConnection()->fetchAssociative($sql, ['id' => $id]);

        return $result === false ? [] : $result;
    }
}
