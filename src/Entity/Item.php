<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection<int, UserItem>
     */
    #[ORM\OneToMany(targetEntity: UserItem::class, mappedBy: 'item', orphanRemoval: true)]
    private Collection $userItems;


    // CONSTRUCTOR
    public function __construct()
    {
        $this->userItems = new ArrayCollection();
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

    /**
     * @return Collection<int, UserItem>
     */
    public function getUserItems(): Collection
    {
        return $this->userItems;
    }
    public function addUserItem(UserItem $userItem): static
    {
        if (!$this->userItems->contains($userItem)) {
            $this->userItems->add($userItem);
            $userItem->setItem($this);
        }

        return $this;
    }
    public function removeUserItem(UserItem $userItem): static
    {
        if ($this->userItems->removeElement($userItem)) {
            // set the owning side to null (unless already changed)
            if ($userItem->getItem() === $this) {
                $userItem->setItem(null);
            }
        }

        return $this;
    }
}
