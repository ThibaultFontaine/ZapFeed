<?php

namespace App\Entity;

use App\Repository\UserFeedRepository;
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

    #[ORM\Column(length: 100)]
    private ?string $title = null;


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
}
