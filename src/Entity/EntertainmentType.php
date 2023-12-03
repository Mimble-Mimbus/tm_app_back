<?php

namespace App\Entity;

use App\Repository\EntertainmentTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntertainmentTypeRepository::class)]
class EntertainmentType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'entertainmentType', targetEntity: Entertainment::class)]
    private Collection $entertainments;

    public function __construct()
    {
        $this->entertainments = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Entertainment>
     */
    public function getEntertainments(): Collection
    {
        return $this->entertainments;
    }

    public function addEntertainment(Entertainment $entertainment): static
    {
        if (!$this->entertainments->contains($entertainment)) {
            $this->entertainments->add($entertainment);
            $entertainment->setEntertainmentType($this);
        }

        return $this;
    }

    public function removeEntertainment(Entertainment $entertainment): static
    {
        if ($this->entertainments->removeElement($entertainment)) {
            // set the owning side to null (unless already changed)
            if ($entertainment->getEntertainmentType() === $this) {
                $entertainment->setEntertainmentType(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->getName();
    }
}
