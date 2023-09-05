<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CatPictureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Uploadable\Mapping\Validator;

#[ORM\Entity(repositoryClass: CatPictureRepository::class), Gedmo\Uploadable(filenameGenerator: Validator::FILENAME_GENERATOR_SHA1)]
class CatPicture
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;
    #[ORM\Column, Gedmo\UploadableFilePath]
    private ?string $path = null;
    #[ORM\Column, Gedmo\UploadableFileName]
    private ?string $name = null;
    #[ORM\Column, Gedmo\UploadableFileMimeType]
    private ?string $mimeType = null;
    #[ORM\Column(type: Types::DECIMAL), Gedmo\UploadableFileSize]
    private ?string $size = null;
    #[ORM\OneToOne(mappedBy: 'catPicture', targetEntity: Kittie::class)]
    private ?Kittie $kittie = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): static
    {
        $this->path = $path;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): static
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): static
    {
        $this->size = $size;
        return $this;
    }

    public function getKittie(): ?Kittie
    {
        return $this->kittie;
    }

    public function setKittie(?Kittie $kittie): static
    {
        $this->kittie = $kittie;
        return $this;
    }
}
