<?php

declare(strict_types=1);

namespace App\UseCase\User\GetUser;

use App\AppBundle\Exception\UserNotFoundException;
use App\AppBundle\Service\UserService;
use Doctrine\DBAL\Exception as DbalException;

readonly class GetUserHandler
{
    public function __construct(private UserService $service)
    {
    }

    /**
     * @throws DbalException
     * @throws UserNotFoundException
     */
    public function handle(string $id): array
    {
        return $this->service->getUser($id);
    }
}
