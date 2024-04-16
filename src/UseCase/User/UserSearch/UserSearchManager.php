<?php

declare(strict_types=1);

namespace App\UseCase\User\UserSearch;

use App\UseCase\AbstractManager;
use Doctrine\DBAL\Exception as DbalException;

use function sprintf;

class UserSearchManager extends AbstractManager
{
    /**
     * @throws DbalException
     */
    public function getUserList(string $first_name, string $last_name): array
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
            WHERE 1
                AND u.name like :first_name
                AND u.last_name like :last_name
            ORDER BY u.id
        ';

        return $this->getConnection()->fetchAllAssociative(
            $sql,
            [
                'first_name' => sprintf('%s%s', $first_name, '%'),
                'last_name' => sprintf('%s%s', $last_name, '%'),
            ]
        );
    }
}
