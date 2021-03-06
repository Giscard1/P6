<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @method string getUserIdentifier()
 * @UniqueEntity(fields={"email"})
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
         * @Assert\Length(
         *      min=2, max=70,
         *      minMessage="Le prénom doit comporter 2 caractères minimum",
         *      maxMessage="Le prénom doit comporter 70 caractères maximum"
         * )
         * @Assert\NotBlank(message = "Le prénom ne peut être vide.")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * * @Assert\Length(
     *      min=2, max=70,
     *      minMessage="Le nom doit comporter 2 caractères minimum",
     *      maxMessage="Le nom doit comporter 70 caractères maximum"
     * )
     * @Assert\NotBlank(message = "Le nom ne peut être vide.")
     */
    private $lastName;

    /**
     * @ORM\OneToMany(targetEntity=Tricks::class, mappedBy="user")
     */
    private $trick;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Le champ mail ne peut être vide.")
     * @Assert\Email(
     *     message = "L'email '{{ value }}' n'est pas un mail valide."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDAte;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateDate;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Le mon de passe ne peut être vide.")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="user")
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    public function __construct()
    {
        $this->trick = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->creationDAte = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Collection|Tricks[]
     */
    public function getTrick(): Collection
    {
        return $this->trick;
    }

    public function addTrick(Tricks $trick): self
    {
        if (!$this->trick->contains($trick)) {
            $this->trick[] = $trick;
            $trick->setUser($this);
        }

        return $this;
    }

    public function removeTrick(Tricks $trick): self
    {
        if ($this->trick->removeElement($trick)) {
            // set the owning side to null (unless already changed)
            if ($trick->getUser() === $this) {
                $trick->setUser(null);
            }
        }

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCreationDAte(): ?\DateTimeInterface
    {
        return $this->creationDAte;
    }

    public function setCreationDAte(\DateTimeInterface $creationDAte): self
    {
        $this->creationDAte = $creationDAte;

        return $this;
    }

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->updateDate;
    }

    public function setUpdateDate(?\DateTimeInterface $updateDate): self
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getSalt()
    {
        return '';
    }

    public function eraseCredentials()
    {
        return;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

}
