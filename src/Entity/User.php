<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // PROPERTIES
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 180, unique: true)]
    private ?string $email = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $password = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    /**
     * @var Collection<int, UserFeed>
     */
    #[ORM\OneToMany(targetEntity: UserFeed::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $userFeeds;

    // /**
    //  * @var Collection<int, Item>
    //  */
    // #[ORM\ManyToMany(targetEntity: Item::class, inversedBy: 'users')]
    // private Collection $items;


    // CONSTRUCTOR
    public function __construct()
    {
        $this->userFeeds = new ArrayCollection();
        // $this->items = new ArrayCollection();
    }


    // GETTERS AND SETTERS
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }
    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    // /**
    //  * @return Collection<int, Item>
    //  */
    // public function getItems(): Collection
    // {
    //     return $this->items;
    // }
    // public function addItem(Item $item): static
    // {
    //     if (!$this->items->contains($item)) {
    //         $this->items->add($item);
    //     }

    //     return $this;
    // }
    // public function removeItem(Item $item): static
    // {
    //     $this->items->removeElement($item);

    //     return $this;
    // }

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
            $userFeed->setUser($this);
        }

        return $this;
    }
    public function removeUserFeed(UserFeed $userFeed): static
    {
        if ($this->userFeeds->removeElement($userFeed)) {
            // set the owning side to null (unless already changed)
            if ($userFeed->getUser() === $this) {
                $userFeed->setUser(null);
            }
        }

        return $this;
    }
}
