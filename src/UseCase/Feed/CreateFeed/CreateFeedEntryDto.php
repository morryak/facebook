<?php

declare(strict_types=1);

namespace App\UseCase\Feed\CreateFeed;

use Symfony\Component\Validator\Constraints as Assert;

class CreateFeedEntryDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'The text should not be blank.')]
        #[Assert\Length(min: 3, max: 255, minMessage: 'The text must be at least {{ limit }} characters long.')]
        public string $text,
    ) {
    }
}