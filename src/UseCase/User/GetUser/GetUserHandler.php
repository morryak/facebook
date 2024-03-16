<?php

declare(strict_types=1);

namespace App\UseCase\User\GetUser;

use Doctrine\DBAL\Exception as DbalException;

readonly class GetUserHandler
{
    public function __construct(private GetUserManager $manager)
    {
    }

    /**
     * @throws DbalException
     * @throws UserNotFoundException
     */
    public function handle(int $id): array
    {
        $user = $this->manager->getUser($id);

        if (empty($user)) {
            throw new UserNotFoundException('User not exist');
        }

        return $user;
    }
}
