<?php

declare(strict_types=1);

namespace App\DTO\RequestDto;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class LoginEntryDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'The id should not be blank.')]
        #[Assert\Length(min: 3, max: 255, minMessage: 'The user name must be at least {{ limit }} characters long.')]
        public string $id,
        #[Assert\NotBlank(message: 'The password should not be blank.')]
        public string $password,
    ) {
    }
}
