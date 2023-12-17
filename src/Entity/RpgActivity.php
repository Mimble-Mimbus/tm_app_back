<?php

namespace App\Entity;

use App\Entity\Abstract\AActivity;
use App\Repository\RpgActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RpgActivityRepository::class)]
class RpgActivity extends AActivity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'rpgActivities')]
    private ?RpgZone $rpgZone = null;

    #[ORM\OneToMany(mappedBy: 'rpgActivity', targetEntity: RpgTable::class, cascade: ['remove'])]
    private Collection $rpgTables;

    public function __construct()
    {
        $this->rpgTables = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRpgZone(): ?RpgZone
    {
        return $this->rpgZone;
    }

    public function setRpgZone(?RpgZone $rpgZone): static
    {
        $this->rpgZone = $rpgZone;

        return $this;
    }

    /**
     * @return Collection<int, RpgTable>
     */
    public function getActivitySchedules(): Collection
    {
        return $this->rpgTables;
    }

    public function addActivitySchedule($rpgTable): static
    {
        if (!$this->rpgTables->contains($rpgTable)) {
            $this->rpgTables->add($rpgTable);
            $rpgTable->setRpgActivity($this);
        }

        return $this;
    }

    public function removeActivitySchedule($rpgTable): static
    {
        if ($this->rpgTables->removeElement($rpgTable)) {
            // set the owning side to null (unless already changed)
            if ($rpgTable->getRpgActivity() === $this) {
                $rpgTable->setRpgActivity(null);
            }
        }

        return $this;
    }
}
