<?php

namespace App\Entity\Abstract;

use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;

#[MappedSuperclass]
abstract class AActivity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $maxNumberSeats = null;

    #[ORM\Column(nullable: true)]
    private ?int $duration = null;

    #[ORM\Column]
    private ?bool $onReservation = null;

    #[ORM\Column]
    private ?bool $isCanceled = null;

    #[ORM\ManyToOne(inversedBy: 'aActivities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AZone $aZone = null;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getMaxNumberSeats(): ?int
    {
        return $this->maxNumberSeats;
    }

    public function setMaxNumberSeats(?int $maxNumberSeats): static
    {
        $this->maxNumberSeats = $maxNumberSeats;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function isOnReservation(): ?bool
    {
        return $this->onReservation;
    }

    public function setOnReservation(bool $onReservation): static
    {
        $this->onReservation = $onReservation;

        return $this;
    }

    public function isIsCanceled(): ?bool
    {
        return $this->isCanceled;
    }

    public function setIsCanceled(bool $isCanceled): static
    {
        $this->isCanceled = $isCanceled;

        return $this;
    }
    /**
     * @return Collection<int, AActivitySchedule>
     */
    abstract public function getActivitieSchedules(): Collection;

    abstract public function addActivitySchedule(AActivitySchedule $activitySchedule): static;

    abstract public function removeActivitySchedule(AActivitySchedule $activitySchedule): static;

    public function getAZone(): ?AZone
    {
        return $this->aZone;
    }

    public function setAZone(?AZone $aZone): static
    {
        $this->aZone = $aZone;

        return $this;
    }

}
