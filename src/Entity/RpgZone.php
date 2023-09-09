<?php

namespace App\Entity;

use App\Repository\RpgZoneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RpgZoneRepository::class)]
class RpgZone extends Zone
{
    #[ORM\Column]
    private ?int $availableTables = null;

    #[ORM\Column(length: 255)]
    private ?string $MinStartHour = null;

    #[ORM\Column(length: 255)]
    private ?string $MaxEndHour = null;

    #[ORM\Column]
    private ?int $MaxAvailableSeatsPerTable = null;

    #[ORM\OneToMany(mappedBy: 'rpgZone', targetEntity: RpgActivity::class)]
    private Collection $rpgActivities;

    public function __construct()
    {
        parent::__construct();
        $this->rpgActivities = new ArrayCollection();
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
        return $this->MinStartHour;
    }

    public function setMinStartHour(string $MinStartHour): static
    {
        $this->MinStartHour = $MinStartHour;

        return $this;
    }

    public function getMaxEndHour(): ?string
    {
        return $this->MaxEndHour;
    }

    public function setMaxEndHour(string $MaxEndHour): static
    {
        $this->MaxEndHour = $MaxEndHour;

        return $this;
    }

    public function getMaxAvailableSeatsPerTable(): ?int
    {
        return $this->MaxAvailableSeatsPerTable;
    }

    public function setMaxAvailableSeatsPerTable(int $MaxAvailableSeatsPerTable): static
    {
        $this->MaxAvailableSeatsPerTable = $MaxAvailableSeatsPerTable;

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
}
