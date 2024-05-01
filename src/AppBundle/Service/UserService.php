<?php

declare(strict_types=1);

namespace App\AppBundle\Service;

use App\AppBundle\Exception\UserNotFoundException;
use App\AppBundle\Manager\UserManager;
use Doctrine\DBAL\Exception;

readonly class UserService
{
    public function __construct(public UserManager $manager)
    {
    }

    /**
     * @throws UserNotFoundException
     * @throws Exception
     */
    public function getUser(string $userId): array
    {
        $user = $this->manager->getUser($userId);

        if (empty($user)) {
            throw new UserNotFoundException('User not exist');
        }

        return $user;
    }
}