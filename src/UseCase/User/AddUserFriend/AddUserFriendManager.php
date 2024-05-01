<?php

declare(strict_types=1);

namespace App\UseCase\User\AddUserFriend;

use App\UseCase\AbstractManager;
use Doctrine\DBAL\Exception as DbalException;

class AddUserFriendManager extends AbstractManager
{
    /**
     * @throws DbalException
     */
    public function addUserFriend(int $userId, string $friendId): void
    {
        $sql = 'INSERT IGNORE INTO friends_list (user_id, friend_id) VALUE (:user_id, :friend_id)';
        $this->getConnection()->executeQuery($sql, ['user_id' => $userId, 'friend_id' => $friendId]);
    }
}
