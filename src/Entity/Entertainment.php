<?php

namespace App\Entity;

use App\Entity\Abstract\AActivity;
use App\Entity\Abstract\AActivitySchedule;
use App\Repository\EntertainmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: EntertainmentRepository::class)]
class Entertainment extends AActivity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'entertainments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EntertainmentType $entertainmentType = null;

    #[ORM\ManyToOne(inversedBy: 'entertainments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Zone $zone = null;

    #[ORM\OneToMany(mappedBy: 'entertainment', targetEntity: EntertainmentSchedule::class)]
    private Collection $entertainmentSchedules;

    public function __construct()
    {
        $this->entertainmentSchedules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntertainmentType(): ?EntertainmentType
    {
        return $this->entertainmentType;
    }

    public function setEntertainmentType(?EntertainmentType $entertainmentType): static
    {
        $this->entertainmentType = $entertainmentType;

        return $this;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): static
    {
        $this->zone = $zone;

        return $this;
    }  

    /**
     * @return Collection<int, EntertainmentSchedule>
     */
    public function getActivitieSchedules(): Collection
    {
        return $this->entertainmentSchedules;
    }

    public function addActivitySchedule($entertainmentSchedule): static
    {
        if (!$this->entertainmentSchedules->contains($entertainmentSchedule)) {
            $this->entertainmentSchedules->add($entertainmentSchedule);
            $entertainmentSchedule->setEntertainment($this);
        }

        return $this;
    }

    public function removeActivitySchedule($entertainmentSchedule): static
    {
        if ($this->entertainmentSchedules->removeElement($entertainmentSchedule)) {
            // set the owning side to null (unless already changed)
            if ($entertainmentSchedule->getEntertainment() === $this) {
                $entertainmentSchedule->setEntertainment(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
