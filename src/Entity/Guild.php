<?php

namespace App\Entity;

use App\Repository\GuildRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GuildRepository::class)]
class Guild
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'guilds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $points = null;

    #[ORM\OneToMany(mappedBy: 'guild', targetEntity: Quest::class)]
    private Collection $quests;

    #[ORM\OneToMany(mappedBy: 'guild', targetEntity: UserTM::class)]
    private Collection $userTMs;

    #[ORM\OneToMany(mappedBy: 'userGuild', targetEntity: StartedQuest::class)]
    private Collection $startedQuests;

    public function __construct()
    {
        $this->quests = new ArrayCollection();
        $this->userTMs = new ArrayCollection();
        $this->startedQuests = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): static
    {
        $this->points = $points;

        return $this;
    }

    /**
     * @return Collection<int, Quest>
     */
    public function getQuests(): Collection
    {
        return $this->quests;
    }

    public function addQuest(Quest $quest): static
    {
        if (!$this->quests->contains($quest)) {
            $this->quests->add($quest);
            $quest->setGuild($this);
        }

        return $this;
    }

    public function removeQuest(Quest $quest): static
    {
        if ($this->quests->removeElement($quest)) {
            // set the owning side to null (unless already changed)
            if ($quest->getGuild() === $this) {
                $quest->setGuild(null);
            }
        }

        return $this;
    }



    /**
     * @return Collection<int, UserTM>
     */
    public function getUserTMs(): Collection
    {
        return $this->userTMs;
    }

    public function addUserTM(UserTM $userTM): static
    {
        if (!$this->userTMs->contains($userTM)) {
            $this->userTMs->add($userTM);
            $userTM->setGuild($this);
        }

        return $this;
    }

    public function removeUserTM(UserTM $userTM): static
    {
        if ($this->userTMs->removeElement($userTM)) {
            // set the owning side to null (unless already changed)
            if ($userTM->getGuild() === $this) {
                $userTM->setGuild(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, StartedQuest>
     */
    public function getStartedQuests(): Collection
    {
        return $this->startedQuests;
    }

    public function addStartedQuest(StartedQuest $startedQuest): static
    {
        if (!$this->startedQuests->contains($startedQuest)) {
            $this->startedQuests->add($startedQuest);
            $startedQuest->setUserGuild($this);
        }

        return $this;
    }

    public function removeStartedQuest(StartedQuest $startedQuest): static
    {
        if ($this->startedQuests->removeElement($startedQuest)) {
            // set the owning side to null (unless already changed)
            if ($startedQuest->getUserGuild() === $this) {
                $startedQuest->setUserGuild(null);
            }
        }

        return $this;
    }
}
