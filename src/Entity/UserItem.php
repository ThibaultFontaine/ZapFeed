<?php

namespace App\Entity;

use App\Repository\UserItemRepository;
use Doctrine\DBAL\Types\Types;
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

    #[ORM\Column(name: 'has_been_read', type: Types::BOOLEAN)]
    private ?bool $hasBeenRead = null;

    #[ORM\Column(name: 'has_been_liked', type: Types::BOOLEAN)]
    private ?bool $hasBeenLiked = null;

    #[ORM\Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: 'updated_at', type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;


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

    public function hasBeenRead(): ?bool
    {
        return $this->hasBeenRead;
    }
    public function setHasBeenRead(bool $hasBeenRead): static
    {
        $this->hasBeenRead = $hasBeenRead;

        return $this;
    }

    public function hasBeenLiked(): ?bool
    {
        return $this->hasBeenLiked;
    }
    public function setHasBeenLiked(bool $hasBeenLiked): static
    {
        $this->hasBeenLiked = $hasBeenLiked;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }
    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
