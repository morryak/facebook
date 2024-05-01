<?php

declare(strict_types=1);

namespace App\UseCase\Feed\CreateFeed;

use App\Entity\User;
use App\UseCase\AbstractManager;
use Doctrine\DBAL\Exception as DbalException;

class CreateFeedManager extends AbstractManager
{
    /**
     * @throws DbalException
     */
    public function createFeedList(?User $user, CreateFeedEntryDto $entryDto): void
    {
        $this->getConnection()->insert('feed', ['text' => $entryDto->text, 'user_id' => $user->getId()]);
    }
}
