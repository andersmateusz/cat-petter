<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\KittieRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: KittieRepository::class), Broadcast]
class Kittie
{
    use BlameableEntity;
    use TimestampableEntity;
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 255), Assert\NotBlank, Assert\Length(max: 255)]
    private ?string $name = null;
    #[ORM\Column(length: 255), Assert\NotBlank, Assert\Length(max: 255)]
    private ?string $breed = null;
    #[ORM\OneToOne(inversedBy: 'kittie', targetEntity: CatPicture::class, cascade: ['persist'], fetch: 'EAGER', orphanRemoval: true)]
    private ?CatPicture $catPicture = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBreed(): ?string
    {
        return $this->breed;
    }

    public function setBreed(string $breed): static
    {
        $this->breed = $breed;

        return $this;
    }

    public function getCatPicture(): ?CatPicture
    {
        return $this->catPicture;
    }

    public function setCatPicture(?CatPicture $catPicture): static
    {
        $this->catPicture = $catPicture;
        $catPicture->setKittie($this);
        return $this;
    }
}
