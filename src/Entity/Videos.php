<?php

namespace App\Entity;

use App\Repository\VideosRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VideosRepository", repositoryClass=VideosRepository::class)
 */
class Videos
{

    public $nature = 'video';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="date")
     */
    private $creationDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $updateDate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $mainVideo;

    /**
     * @ORM\ManyToOne(targetEntity=Tricks::class, inversedBy="videos")
     */
    private $tricks;

    /**
     * Videos constructor.
     */
    public function __construct()
    {
        $this->setCreationDate(new \DateTime());
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

    public function getMainVideo(): ?bool
    {
        return $this->mainVideo;
    }

    public function setMainVideo(?bool $mainVideo): self
    {
        $this->mainVideo = $mainVideo;

        return $this;
    }

    public function getTricks(): ?Tricks
    {
        return $this->tricks;
    }

    public function setTricks(?Tricks $tricks): self
    {
        $this->tricks = $tricks;

        return $this;
    }

    public function __toString()
    {
        return (string)$this->name;
    }

}
