<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity]
#[ORM\UniqueConstraint(name: 'uniq_token', columns: ['token'])]
class Token extends AbstractEntity
{
    #[ORM\Column(length: 255)]
    private string $token;
}
