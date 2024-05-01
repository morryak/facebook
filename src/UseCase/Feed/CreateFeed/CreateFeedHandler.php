<?php

declare(strict_types=1);

namespace App\UseCase\Feed\CreateFeed;

use App\Entity\User;
use Doctrine\DBAL\Exception as DbalException;

readonly class CreateFeedHandler
{
    public function __construct(private CreateFeedManager $manager)
    {
    }

    /**
     * @throws DbalException
     */
    public function handle(?User $user, CreateFeedEntryDto $entryDto): void
    {
        $this->manager->createFeedList($user, $entryDto);
    }
}
