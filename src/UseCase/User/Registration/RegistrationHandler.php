<?php

declare(strict_types=1);

namespace App\UseCase\User\Registration;

use App\DTO\RequestDto\RegistrationEntryDto;
use App\Entity\User;
use Doctrine\DBAL\Exception as DbalException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exception;
use RuntimeException;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

readonly class RegistrationHandler
{
    public function __construct(
        private RegistrationManager $manager,
        private PasswordHasherFactoryInterface $encoder
    ) {
    }

    /**
     * @throws DbalException
     * @throws Exception
     */
    public function handle(RegistrationEntryDto $entryDto): string
    {
        $user = [
            'name' => $entryDto->first_name,
            'email' => $entryDto->email,
            'last_name' => $entryDto->second_name,
            'password' => $this->encoder->getPasswordHasher(new User())->hash($entryDto->password, null),
            'sex' => $entryDto->sex,
            'city' => $entryDto->city,
            'birth_date' => $entryDto->birthDate,
            'roles' => '',
            'biography' => $entryDto->biography,
        ];

        try {
            return $this->manager->insertUser($user);
        } catch (UniqueConstraintViolationException) {
            throw new RuntimeException('Current email are already used');
        }
    }
}
