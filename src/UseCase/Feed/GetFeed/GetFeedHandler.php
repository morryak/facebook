<?php

declare(strict_types=1);

namespace App\UseCase\Feed\GetFeed;

use App\Entity\User;
use Doctrine\DBAL\Exception as DbalException;

readonly class GetFeedHandler
{
    public function __construct(private GetFeedManager $manager)
    {
    }

    /**
     * @throws DbalException
     */
    public function handle(?User $user, int $limit, int $offset): array
    {
        // todo добавить кеширование
        return $this->manager->getFeedList($user, $limit, $offset);
    }
}
