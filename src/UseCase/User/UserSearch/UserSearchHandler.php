<?php

declare(strict_types=1);

namespace App\UseCase\User\UserSearch;

use App\UseCase\User\GetUser\UserNotFoundException;
use Doctrine\DBAL\Exception as DbalException;

readonly class UserSearchHandler
{
    public function __construct(private UserSearchManager $manager)
    {
    }

    /**
     * @throws DbalException
     * @throws UserNotFoundException
     */
    public function handle(string $first_name, string $last_name): array
    {
        $user = $this->manager->getUserList($first_name, $last_name);

        if (empty($user)) {
            throw new UserNotFoundException('User not exist');
        }

        return $user;
    }
}
