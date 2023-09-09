<?php

namespace App\Entity;

use App\Repository\QuestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestRepository::class)]
class Quest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'quests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\ManyToOne(inversedBy: 'quests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Zone $zone = null;

    #[ORM\ManyToOne(inversedBy: 'quests')]
    private ?Guild $guild = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $infos = null;

    #[ORM\Column]
    private ?int $points = null;

    #[ORM\Column]
    private ?bool $isSecret = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'quest', targetEntity: FullfilledQuest::class)]
    private Collection $fullfilledQuests;

    public function __construct()
    {
        $this->fullfilledQuests = new ArrayCollection();
    }

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

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): static
    {
        $this->zone = $zone;

        return $this;
    }

    public function getGuild(): ?Guild
    {
        return $this->guild;
    }

    public function setGuild(?Guild $guild): static
    {
        $this->guild = $guild;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getInfos(): ?string
    {
        return $this->infos;
    }

    public function setInfos(string $infos): static
    {
        $this->infos = $infos;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): static
    {
        $this->points = $points;

        return $this;
    }

    public function isIsSecret(): ?bool
    {
        return $this->isSecret;
    }

    public function setIsSecret(bool $isSecret): static
    {
        $this->isSecret = $isSecret;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, FullfilledQuest>
     */
    public function getFullfilledQuests(): Collection
    {
        return $this->fullfilledQuests;
    }

    public function addFullfilledQuest(FullfilledQuest $fullfilledQuest): static
    {
        if (!$this->fullfilledQuests->contains($fullfilledQuest)) {
            $this->fullfilledQuests->add($fullfilledQuest);
            $fullfilledQuest->setQuest($this);
        }

        return $this;
    }

    public function removeFullfilledQuest(FullfilledQuest $fullfilledQuest): static
    {
        if ($this->fullfilledQuests->removeElement($fullfilledQuest)) {
            // set the owning side to null (unless already changed)
            if ($fullfilledQuest->getQuest() === $this) {
                $fullfilledQuest->setQuest(null);
            }
        }

        return $this;
    }
}
