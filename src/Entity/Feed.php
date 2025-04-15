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
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $name;

    #[ORM\Column(type: Types::TEXT)]
    private string $url;

    /**
     * @var Collection<int, Tag>
     */
    // #[ORM\OneToMany(targetEntity: Tag::class, mappedBy: 'feed', orphanRemoval: true)]
    // private Collection $tags;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'feeds')]
    private Collection $users;

    /**
     * @var Collection<int, FeedItem>
     */
    #[ORM\OneToMany(targetEntity: FeedItem::class, mappedBy: 'feed', orphanRemoval: true)]
    private Collection $feedItems;

    public function __construct()
    {
        // $this->tags = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->feedItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    /**
     * @return Collection<int, Tag>
     */
    // public function getTags(): Collection
    // {
    //     return $this->tags;
    // }

    // public function addTag(Tag $tag): static
    // {
    //     if (!$this->tags->contains($tag)) {
    //         $this->tags->add($tag);
    //         $tag->setFeedId($this);
    //     }

    //     return $this;
    // }

    // public function removeTag(Tag $tag): static
    // {
    //     if ($this->tags->removeElement($tag)) {
    //         // set the owning side to null (unless already changed)
    //         if ($tag->getFeedId() === $this) {
    //             $tag->setFeedId(null);
    //         }
    //     }

    //     return $this;
    // }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addFeed($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeFeed($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, FeedItem>
     */
    public function getFeedItems(): Collection
    {
        return $this->feedItems;
    }

    public function addFeedItem(FeedItem $feedItem): static
    {
        if (!$this->feedItems->contains($feedItem)) {
            $this->feedItems->add($feedItem);
            $feedItem->setFeed($this);
        }

        return $this;
    }

    public function removeFeedItem(FeedItem $feedItem): static
    {
        if ($this->feedItems->removeElement($feedItem)) {
            // set the owning side to null (unless already changed)
            if ($feedItem->getFeed() === $this) {
                $feedItem->setFeed(null);
            }
        }

        return $this;
    }
}
