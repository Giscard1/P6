<?php

namespace App\Entity;

use App\Repository\PicturesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass=PicturesRepository::class)
 */
class Pictures
{

    public $nature = 'picture';

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


    private UploadedFile $imageFile;

    /**
     * @return UploadedFile
     */
    public function getImageFile(): UploadedFile
    {
        return $this->imageFile;
    }


    /**
     * @param UploadedFile $imageFile
     */
    public function setImageFile(UploadedFile $imageFile): void
    {
        $this->imageFile = $imageFile;
    }

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Tricks::class, inversedBy="picture")
     */
    private $tricks;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateDate;

    /**
     * Pictures constructor.
     */

   /*
    *  public function __construct($mainImage)
    {
        $this->mainImage = $mainImage;
    }

    */
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

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function __toString()
    {
        return (string)$this->name;
    }

}
