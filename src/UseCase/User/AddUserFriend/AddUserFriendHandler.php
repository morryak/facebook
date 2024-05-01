<?php

declare(strict_types=1);

namespace App\UseCase\User\AddUserFriend;

use App\AppBundle\Exception\UserNotFoundException;
use App\AppBundle\Service\UserService;
use App\Entity\User;
use Doctrine\DBAL\Exception as DbalException;

readonly class AddUserFriendHandler
{
    public function __construct(private UserService $service, private AddUserFriendManager $manager)
    {
    }

    /**
     * @throws DbalException
     * @throws UserNotFoundException
     */
    public function handle(?User $user, string $friendId): void
    {
        $this->service->getUser($friendId);
        $this->manager->addUserFriend($user->getId(), $friendId);
    }
}
