<?php

declare(strict_types=1);

namespace App\UseCase\User\LoginUser;

use App\UseCase\AbstractManager;
use Doctrine\DBAL\Exception;

class LoginManager extends AbstractManager
{
    /**
     * @throws Exception
     */
    public function saveUserToken(array $params): void
    {
        $this->getConnection()->insert('user_token', $params);
    }

    /**
     * @throws Exception
     */
    public function getActiveUserTokenId(int $userId): ?int
    {
        $sql = 'SELECT u.id FROM user_token u where u.user_id = :userId';

        $result = $this->getConnection()->fetchOne($sql, ['userId' => $userId]);

        return $result === false ? null : $result;
    }

    /**
     * @throws Exception
     */
    public function deactivateUserTokenId(array $params, array $where): void
    {
        $this->getConnection()->update('user_token', $params, $where);
    }
}
