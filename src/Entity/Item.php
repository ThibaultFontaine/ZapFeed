<?php

namespace App\Entity;

use App\Repository\ItemRepository;
// use Doctrine\Common\Collections\ArrayCollection;
// use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    // PROPERTIES
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $url = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $mediaLink = null;

    #[ORM\ManyToOne(targetEntity: Feed::class, inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Feed $feed = null;

    // /**
    //  * @var Collection<int, User>
    //  */
    // #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'items')]
    // private Collection $users;


    // CONSTRUCTOR
    public function __construct()
    {
        // $this->users = new ArrayCollection();
    }


    // GETTERS & SETTERS
    public function getId(): ?int
    {
        return $this->id;
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

    public function getUrl(): ?string
    {
        return $this->url;
    }
    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getMediaLink(): ?string
    {
        return $this->mediaLink;
    }
    public function setMediaLink(?string $mediaLink): static
    {
        $this->mediaLink = $mediaLink;

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

    // /**
    //  * @return Collection<int, User>
    //  */
    // public function getUsers(): Collection
    // {
    //     return $this->users;
    // }
    // public function addUser(User $user): static
    // {
    //     if (!$this->users->contains($user)) {
    //         $this->users->add($user);
    //         $user->addItem($this);
    //     }

    //     return $this;
    // }
    // public function removeUser(User $user): static
    // {
    //     if ($this->users->removeElement($user)) {
    //         $user->removeItem($this);
    //     }

    //     return $this;
    // }
}
