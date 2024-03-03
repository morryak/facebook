<?php

declare(strict_types=1);

namespace App\DTO\RequestDto;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class RegistrationEntryDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'The user name should not be blank.')]
        #[Assert\Length(min: 3, max: 255, minMessage: 'The user name must be at least {{ limit }} characters long.')]
        public string $userName,
        #[Assert\NotBlank(message: 'The pass word should not be blank.')]
        public string $passWord,
    ) {
    }
}
