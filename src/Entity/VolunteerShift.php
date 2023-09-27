<?php

namespace App\Entity;

use App\Entity\UserTM;
use App\Repository\VolunteerShiftRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VolunteerShiftRepository::class)]
class VolunteerShift
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'volunteerShifts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserTM $user = null;

    #[ORM\ManyToOne(inversedBy: 'volunteerShifts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\ManyToOne(inversedBy: 'volunteerShifts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Zone $zone = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $shiftStart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $shiftEnd = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?UserTM
    {
        return $this->user;
    }

    public function setUser(?UserTM $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): static
    {
        $this->event = $event;

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

    public function getShiftStart(): ?\DateTimeInterface
    {
        return $this->shiftStart;
    }

    public function setShiftStart(\DateTimeInterface $shiftStart): static
    {
        $this->shiftStart = $shiftStart;

        return $this;
    }

    public function getShiftEnd(): ?\DateTimeInterface
    {
        return $this->shiftEnd;
    }

    public function setShiftEnd(\DateTimeInterface $shiftEnd): static
    {
        $this->shiftEnd = $shiftEnd;

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
}
