<?php

declare(strict_types=1);

namespace App\DTO\RequestDto;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class RegistrationEntryDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'The user name should not be blank.')]
        #[Assert\Length(min: 3, max: 255, minMessage: 'The user name must be at least {{ limit }} characters long.')]
        public string $first_name,
        #[Assert\NotBlank(message: 'The email should not be blank.')]
        #[Assert\Length(min: 3, max: 255, minMessage: 'The user name must be at least {{ limit }} characters long.')]
        #[Assert\Email]
        public string $email,
        #[Assert\NotBlank(message: 'The last name should not be blank.')]
        #[Assert\Length(min: 3, max: 255, minMessage: 'The last name must be at least {{ limit }} characters long.')]
        public string $second_name,
        #[Assert\NotBlank(message: 'The password should not be blank.')]
        public string $password,
        #[Assert\NotBlank(message: 'The sex should not be blank.')]
        #[Assert\Choice(choices: ['male', 'women'])]
        public string $sex,
        #[Assert\NotBlank(message: 'The city should not be blank.')]
        #[Assert\Length(min: 2, max: 255, minMessage: 'The city must be at least {{ limit }} characters long.')]
        public string $city,
        #[Assert\NotBlank(message: 'The city should not be blank.')]
        #[Assert\Length(max: 255, minMessage: 'The biography be at least {{ limit }} characters long.')]
        public string $biography,
        #[Assert\Date()]
        public string $birthDate,
    ) {
    }
}
