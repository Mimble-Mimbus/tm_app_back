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

    #[ORM\ManyToOne(inversedBy: 'rpgActivity')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserTM $userGm = null;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'rpgActivity')]
    private Collection $tags;

    #[ORM\ManyToMany(targetEntity: TriggerWarning::class, inversedBy: 'rpgActivity')]
    private Collection $triggerWarnings;

    #[ORM\ManyToOne(inversedBy: 'rpgActivity')]
    private ?Rpg $rpg = null;

    public function __construct()
    {
        $this->rpgTables = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->triggerWarnings = new ArrayCollection();
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

    public function getUserGm(): ?UserTM
    {
        return $this->userGm;
    }

    public function setUserGm(?UserTM $userGm): static
    {
        $this->userGm = $userGm;

        return $this;
    }

    /**
     * @return Collection<int, TriggerWarning>
     */
    public function getTriggerWarnings(): Collection
    {
        return $this->triggerWarnings;
    }

    public function addTriggerWarning(TriggerWarning $triggerWarning): static
    {
        if (!$this->triggerWarnings->contains($triggerWarning)) {
            $this->triggerWarnings->add($triggerWarning);
        }

        return $this;
    }

    public function removeTriggerWarning(TriggerWarning $triggerWarning): static
    {
        $this->triggerWarnings->removeElement($triggerWarning);

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getRpg(): ?Rpg
    {
        return $this->rpg;
    }

    public function setRpg(?Rpg $rpg): static
    {
        $this->rpg = $rpg;

        return $this;
    }
}
