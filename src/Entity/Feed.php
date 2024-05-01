<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity]
#[Index(name: 'user_id', columns: ['user_id'])]
class Feed extends AbstractEntity
{
    #[ORM\Column(type: 'text')]
    private string $text;

    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, options: ['unsigned' => true])]
    private User $user;
}
