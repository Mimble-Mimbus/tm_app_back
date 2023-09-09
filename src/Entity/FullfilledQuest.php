<?php

namespace App\Entity;

use App\Repository\FullfilledQuestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FullfilledQuestRepository::class)]
class FullfilledQuest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'fullfilledQuests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quest $quest = null;

    #[ORM\ManyToOne(inversedBy: 'fullfilledQuests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserTM $user = null;

    #[ORM\Column(nullable: true)]
    private ?int $userGuild = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuest(): ?Quest
    {
        return $this->quest;
    }

    public function setQuest(?Quest $quest): static
    {
        $this->quest = $quest;

        return $this;
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

    public function getUserGuild(): ?int
    {
        return $this->userGuild;
    }

    public function setUserGuild(?int $userGuild): static
    {
        $this->userGuild = $userGuild;

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
}
