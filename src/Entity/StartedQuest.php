<?php

namespace App\Entity;

use App\Repository\StartedQuestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StartedQuestRepository::class)]
class StartedQuest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'startedQuests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quest $quest = null;

    #[ORM\ManyToOne(inversedBy: 'startedQuests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserTM $user = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'startedQuests')]
    private ?Guild $userGuild = null;

    #[ORM\Column]
    private ?bool $isFulfilled = null;

    #[ORM\Column]
    private ?bool $isAborted = null;

    #[ORM\Column(nullable: true)]
    private ?int $difficulty = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

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

    public function isIsFulfilled(): ?bool
    {
        return $this->isFulfilled;
    }

    public function setIsFulfilled(bool $isFulfilled): static
    {
        $this->isFulfilled = $isFulfilled;

        return $this;
    }

    public function isIsAborted(): ?bool
    {
        return $this->isAborted;
    }

    public function setIsAborted(bool $isAborted): static
    {
        $this->isAborted = $isAborted;

        return $this;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(?int $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }
}
