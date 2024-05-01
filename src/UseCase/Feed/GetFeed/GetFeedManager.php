<?php

declare(strict_types=1);

namespace App\UseCase\Feed\GetFeed;

use App\Entity\User;
use App\UseCase\AbstractManager;
use Doctrine\DBAL\Exception as DbalException;
use Doctrine\DBAL\ParameterType;

class GetFeedManager extends AbstractManager
{
    /**
     * @throws DbalException
     */
    public function getFeedList(?User $user, int $limit, int $offset): array
    {
        $sql = '
            SELECT 
                f.text,
                f.id,
                f.user_id AS author_user_id
            FROM feed f
            INNER JOIN friends_list fl ON fl.friend_id = f.user_id  
            WHERE fl.user_id = :user_id
            LIMIT :limit
            OFFSET :offset
        ';

        return $this->getConnection()->fetchAllAssociative(
            $sql,
            ['limit' => $limit, 'offset' => $offset, 'user_id' => $user->getId()],
            ['limit' => ParameterType::INTEGER, 'offset' => ParameterType::INTEGER],
        );
    }
}
