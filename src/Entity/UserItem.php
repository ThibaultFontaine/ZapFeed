<?php

namespace App\Entity;

use App\Repository\UserItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'user_item')]
#[ORM\Entity(repositoryClass: UserItemRepository::class)]
class UserItem
{
    // PROPERTIES
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Item::class, inversedBy: 'userItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Item $item = null;


    // GETTER AND SETTER
    public function getUser(): ?User
    {
        return $this->user;
    }
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }
    public function setItem(?Item $item): static
    {
        $this->item = $item;

        return $this;
    }
}
