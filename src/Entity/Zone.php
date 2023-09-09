<?php

namespace App\Entity;

use App\Entity\event;
use App\Repository\ZoneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;

#[ORM\Entity(repositoryClass: ZoneRepository::class)]
#[MappedSuperclass(repositoryClass: ZoneRepository::class)]
class Zone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'zones')]
    #[ORM\JoinColumn(nullable: false)]
    protected ?event $event = null;

    #[ORM\Column(length: 255)]
    protected ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'zone', targetEntity: Entertainment::class)]
    private Collection $entertainments;

    #[ORM\OneToMany(mappedBy: 'zone', targetEntity: Quest::class)]
    private Collection $quests;

    public function __construct()
    {
        $this->entertainments = new ArrayCollection();
        $this->quests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvent(): ?event
    {
        return $this->event;
    }

    public function setEvent(?event $event): static
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

    /**
     * @return Collection<int, Entertainment>
     */
    public function getEntertainments(): Collection
    {
        return $this->entertainments;
    }

    public function addEntertainment(Entertainment $entertainment): static
    {
        if (!$this->entertainments->contains($entertainment)) {
            $this->entertainments->add($entertainment);
            $entertainment->setZone($this);
        }

        return $this;
    }

    public function removeEntertainment(Entertainment $entertainment): static
    {
        if ($this->entertainments->removeElement($entertainment)) {
            // set the owning side to null (unless already changed)
            if ($entertainment->getZone() === $this) {
                $entertainment->setZone(null);
            }
        }

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
            $quest->setZone($this);
        }

        return $this;
    }

    public function removeQuest(Quest $quest): static
    {
        if ($this->quests->removeElement($quest)) {
            // set the owning side to null (unless already changed)
            if ($quest->getZone() === $this) {
                $quest->setZone(null);
            }
        }

        return $this;
    }
}
