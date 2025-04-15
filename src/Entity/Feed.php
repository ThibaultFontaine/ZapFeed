<?php

namespace App\Entity;

use App\Repository\FeedRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FeedRepository::class)]
class Feed
{
    // PROPERTIES
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, unique: true)]
    private ?string $url = null;

    /**
     * @var Collection<int, Item>
     */
    #[ORM\OneToMany(targetEntity: Item::class, mappedBy: 'feed', orphanRemoval: true)]
    private Collection $items;

    /**
     * @var Collection<int, UserFeed>
     */
    #[ORM\OneToMany(targetEntity: UserFeed::class, mappedBy: 'feed', orphanRemoval: true)]
    private Collection $userFeeds;


    // CONSTRUCTOR
    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->userFeeds = new ArrayCollection();
    }


    // GETTERS AND SETTERS
    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Item>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }
    public function addItem(Item $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setFeed($this);
        }

        return $this;
    }
    public function removeItem(Item $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getFeed() === $this) {
                $item->setFeed(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserFeed>
     */
    public function getUserFeeds(): Collection
    {
        return $this->userFeeds;
    }
    public function addUserFeed(UserFeed $userFeed): static
    {
        if (!$this->userFeeds->contains($userFeed)) {
            $this->userFeeds->add($userFeed);
            $userFeed->setFeed($this);
        }

        return $this;
    }
    public function removeUserFeed(UserFeed $userFeed): static
    {
        if ($this->userFeeds->removeElement($userFeed)) {
            // set the owning side to null (unless already changed)
            if ($userFeed->getFeed() === $this) {
                $userFeed->setFeed(null);
            }
        }

        return $this;
    }
}
