<?php

namespace App\Entity;

use App\Repository\UserFeedRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'user_feed')]
#[ORM\Entity(repositoryClass: UserFeedRepository::class)]
class UserFeed
{
    // PROPERTIES
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'userFeeds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'userFeeds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Feed $feed = null;

    #[ORM\Column(type: Types::STRING, length: 100)]
    private ?string $title = null;

    #[ORM\Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: 'updated_at', type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;


    // GETTERS AND SETTERS
    public function getUser(): ?User
    {
        return $this->user;
    }
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getFeed(): ?Feed
    {
        return $this->feed;
    }
    public function setFeed(?Feed $feed): static
    {
        $this->feed = $feed;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
    public function setTitle(string $title): static
    {
        $this->title = $title;

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
