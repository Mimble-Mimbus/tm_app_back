<?php

namespace App\Entity;

use App\Entity\Abstract\AZone;
use App\Repository\RpgZoneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RpgZoneRepository::class)]
class RpgZone extends AZone
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\Column]
    private ?int $availableTables = null;

    #[ORM\Column(length: 255)]
    private ?string $minStartHour = null;

    #[ORM\Column(length: 255)]
    private ?string $maxEndHour = null;

    #[ORM\Column]
    private ?int $maxAvailableSeatsPerTable = null;

    #[ORM\OneToMany(mappedBy: 'rpgZone', targetEntity: RpgActivity::class, cascade: ['remove'])]
    private Collection $rpgActivities;

    #[ORM\ManyToOne(inversedBy: 'rpgZones')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\OneToOne(inversedBy: 'rpgZone', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Zone $zone = null;

    public function __construct()
    {
        $this->rpgActivities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAvailableTables(): ?int
    {
        return $this->availableTables;
    }

    public function setAvailableTables(int $availableTables): static
    {
        $this->availableTables = $availableTables;

        return $this;
    }

    public function getMinStartHour(): ?string
    {
        return $this->minStartHour;
    }

    public function setMinStartHour(string $minStartHour): static
    {
        $this->minStartHour = $minStartHour;

        return $this;
    }

    public function getMaxEndHour(): ?string
    {
        return $this->maxEndHour;
    }

    public function setMaxEndHour(string $maxEndHour): static
    {
        $this->maxEndHour = $maxEndHour;

        return $this;
    }

    public function getMaxAvailableSeatsPerTable(): ?int
    {
        return $this->maxAvailableSeatsPerTable;
    }

    public function setMaxAvailableSeatsPerTable(int $maxAvailableSeatsPerTable): static
    {
        $this->maxAvailableSeatsPerTable = $maxAvailableSeatsPerTable;

        return $this;
    }

    /**
     * @return Collection<int, RpgActivity>
     */
    public function getRpgActivities(): Collection
    {
        return $this->rpgActivities;
    }

    public function addRpgActivity(RpgActivity $rpgActivity): static
    {
        if (!$this->rpgActivities->contains($rpgActivity)) {
            $this->rpgActivities->add($rpgActivity);
            $rpgActivity->setRpgZone($this);
        }

        return $this;
    }

    public function removeRpgActivity(RpgActivity $rpgActivity): static
    {
        if ($this->rpgActivities->removeElement($rpgActivity)) {
            // set the owning side to null (unless already changed)
            if ($rpgActivity->getRpgZone() === $this) {
                $rpgActivity->setRpgZone(null);
            }
        }

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

    public function setZone(Zone $zone): static
    {
        $this->zone = $zone;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
