<?php

namespace App\Entity;

use App\Entity\Abstract\AUser;
use App\Entity\Abstract\AZone;
use App\Repository\ReportingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReportingRepository::class)]
class Reporting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reportings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AZone $zone = null;

    #[ORM\ManyToOne(inversedBy: 'reportings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AUser $user = null;

    #[ORM\ManyToOne(inversedBy: 'reportings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column(length: 255)]
    private ?string $emergencyLevel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getZone(): ?AZone
    {
        return $this->zone;
    }

    public function setZone(?AZone $zone): static
    {
        $this->zone = $zone;

        return $this;
    }

    public function getUser(): ?AUser
    {
        return $this->user;
    }

    public function setUser(?AUser $user): static
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getEmergencyLevel(): ?string
    {
        return $this->emergencyLevel;
    }

    public function setEmergencyLevel(string $emergencyLevel): static
    {
        $this->emergencyLevel = $emergencyLevel;

        return $this;
    }
}
