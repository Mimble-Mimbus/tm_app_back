<?php

namespace App\Entity;

use App\Repository\FulfilledQuestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FulfilledQuestRepository::class)]
class FulfilledQuest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'fulfilledQuests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quest $quest = null;

    #[ORM\ManyToOne(inversedBy: 'fulfilledQuests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserTM $user = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'fulfilledQuests')]
    private ?Guild $userGuild = null;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getUserGuild(): ?Guild
    {
        return $this->userGuild;
    }

    public function setUserGuild(?Guild $userGuild): static
    {
        $this->userGuild = $userGuild;

        return $this;
    }
}
