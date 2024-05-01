<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[ORM\Entity]
#[UniqueConstraint(name: 'uniq_user_id_friend_id', columns: ['user_id', 'friend_id'])]
class FriendsList extends AbstractEntity
{
    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, options: ['unsigned' => true])]
    private User $user;

    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'friend_id', referencedColumnName: 'id', nullable: false, options: ['unsigned' => true])]
    private User $friendId;
}
