<?php

namespace App\Entity;

use App\Repository\OpenDayRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OpenDayRepository::class)]
class OpenDay
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'openDays')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dayStart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dayEnd = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDayStart(): ?\DateTimeInterface
    {
        return $this->dayStart;
    }

    public function setDayStart(\DateTimeInterface $dayStart): static
    {
        $this->dayStart = $dayStart;

        return $this;
    }

    public function getDayEnd(): ?\DateTimeInterface
    {
        return $this->dayEnd;
    }

    public function setDayEnd(\DateTimeInterface $dayEnd): static
    {
        $this->dayEnd = $dayEnd;

        return $this;
    }
}
