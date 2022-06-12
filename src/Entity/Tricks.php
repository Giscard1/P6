<?php

namespace App\Entity;

use App\Repository\TricksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\TricksRepository", repositoryClass=TricksRepository::class)
 * @UniqueEntity(fields={"name"})
 */
class Tricks
{
    public const LIMIT_PER_PAGE = 15;
    public const LIMIT_COMMENT_PER_PAGE = 3;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * * @Assert\Length(
     *      min=2, max=70,
     *      minMessage="Le nom doit comporter 2 caractères minimum",
     *      maxMessage="Le nom doit comporter 70 caractères maximum"
     * )
     * @Assert\NotBlank(message = "Le nom ne peut être vide.")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * * @ORM\Column(type="string", length=255)
     * * @Assert\Length(
     *      min=2, max=255,
     *      minMessage="La description doit comporter 2 caractères minimum",
     *      maxMessage="Le description doit comporter 255 caractères maximum"
     * )
     * @Assert\NotBlank(message = "La description ne peut être vide.")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="tricks",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message = "La catégorie ne peut être vide.")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=Pictures::class, mappedBy="tricks", cascade={"persist", "remove"})
     */
    private $picture;

    /**
     * @ORM\OneToMany(targetEntity=Steps::class, mappedBy="tricks")
     */
    private $steps;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="tricks")
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="trick")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateDate;

    /**
     * @ORM\OneToMany(targetEntity=Videos::class, mappedBy="tricks",cascade={"persist"})
     */
    private $videos;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $alt;

    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mainPicture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @return mixed
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param mixed $imageFile
     */
    public function setImageFile($imageFile): void
    {
        $this->imageFile = $imageFile;
    }

    /*
     * @return UploadedFile

    public function getImageFile(): UploadedFile
    {
        return $this->imageFile;
    }
*/

    public function __construct()
    {
        $this->picture = new ArrayCollection();
        $this->steps = new ArrayCollection();
        $this->comment = new ArrayCollection();
        $this->creationDate = new \DateTime();
        $this->videos = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Pictures[]
     */
    public function getPicture(): Collection
    {
        return $this->picture;
    }

    public function addPicture(Pictures $picture): self
    {
        if (!$this->picture->contains($picture)) {
            $this->picture[] = $picture;
            $picture->setTricks($this);
        }

        return $this;
    }

    public function removePicture(Pictures $picture): self
    {
        if ($this->picture->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getTricks() === $this) {
                $picture->setTricks(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Steps[]
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function addStep(Steps $step): self
    {
        if (!$this->steps->contains($step)) {
            $this->steps[] = $step;
            $step->setTricks($this);
        }

        return $this;
    }

    public function removeStep(Steps $step): self
    {
        if ($this->steps->removeElement($step)) {
            // set the owning side to null (unless already changed)
            if ($step->getTricks() === $this) {
                $step->setTricks(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comment->contains($comment)) {
            $this->comment[] = $comment;
            $comment->setTricks($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTricks() === $this) {
                $comment->setTricks(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

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

    /**
     * @return Collection|Videos[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Videos $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setTricks($this);
        }

        return $this;
    }

    public function removeVideo(Videos $video): self
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getTricks() === $this) {
                $video->setTricks(null);
            }
        }

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

    public function __toString()
    {
        return (string)$this->videos;
    }

    public function getMainPicture(): ?string
    {
        return $this->mainPicture;
    }

    public function setMainPicture(?string $mainPicture): self
    {
        $this->mainPicture = $mainPicture;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

}
