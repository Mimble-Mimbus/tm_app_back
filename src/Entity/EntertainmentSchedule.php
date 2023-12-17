<?php

namespace App\Entity;

use App\Entity\Abstract\AActivitySchedule;
use App\Repository\EntertainmentScheduleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntertainmentScheduleRepository::class)]
class EntertainmentSchedule extends AActivitySchedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'entertainmentSchedules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Entertainment $entertainment = null;

    #[ORM\OneToMany(mappedBy: 'entertainmentSchedule', targetEntity: EntertainmentReservation::class, cascade: ['remove'])]
    private Collection $entertainmentReservations;

    public function __construct()
    {
        $this->entertainmentReservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntertainment(): ?Entertainment
    {
        return $this->entertainment;
    }

    public function setEntertainment(?Entertainment $entertainment): static
    {
        $this->entertainment = $entertainment;

        return $this;
    }

    

    /**
     * @return Collection<int, EntertainmentReservation>
     */
    public function getActivityReservations(): Collection
    {
        return $this->entertainmentReservations;
    }

    public function addActivityReservation($entertainmentReservation): static
    {
        if (!$this->entertainmentReservations->contains($entertainmentReservation)) {
            $this->entertainmentReservations->add($entertainmentReservation);
            $entertainmentReservation->setEntertainmentSchedule($this);
        }

        return $this;
    }

    public function removeActivityReservation($entertainmentReservation): static
    {
        if ($this->entertainmentReservations->removeElement($entertainmentReservation)) {
            // set the owning side to null (unless already changed)
            if ($entertainmentReservation->getEntertainmentSchedule() === $this) {
                $entertainmentReservation->setEntertainmentSchedule(null);
            }
        }

        return $this;
    }

    public function __toString() {
        $start = $this->getStart()->format('d-M-Y H:i');
        return $start;
    }
}
