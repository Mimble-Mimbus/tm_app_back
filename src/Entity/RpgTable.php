<?php

namespace App\Entity;

use App\Entity\Abstract\AActivitySchedule;
use App\Repository\RpgTableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RpgTableRepository::class)]
class RpgTable extends AActivitySchedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'rpgTables')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RpgActivity $rpgActivity = null;

    #[ORM\ManyToOne(inversedBy: 'rpgTables')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserTM $userGm = null;

    #[ORM\OneToMany(mappedBy: 'rpgTable', targetEntity: RpgReservation::class)]
    private Collection $rpgReservations;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'rpgTables')]
    private Collection $tags;

    #[ORM\ManyToMany(targetEntity: TriggerWarning::class, inversedBy: 'rpgTables')]
    private Collection $triggerWarnings;

    #[ORM\ManyToOne(inversedBy: 'rpgTables')]
    private ?Rpg $rpg = null;

    public function __construct()
    {
        $this->rpgReservations = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->triggerWarnings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRpgActivity(): ?RpgActivity
    {
        return $this->rpgActivity;
    }

    public function setRpgActivity(?RpgActivity $rpgActivity): static
    {
        $this->rpgActivity = $rpgActivity;

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
     * @return Collection<int, RpgReservation>
     */
    public function getActivityReservations(): Collection
    {
        return $this->rpgReservations;
    }

    public function addActivityReservation($rpgReservation): static
    {
        if (!$this->rpgReservations->contains($rpgReservation)) {
            $this->rpgReservations->add($rpgReservation);
            $rpgReservation->setRpgTable($this);
        }

        return $this;
    }

    public function removeActivityReservation($rpgReservation): static
    {
        if ($this->rpgReservations->removeElement($rpgReservation)) {
            // set the owning side to null (unless already changed)
            if ($rpgReservation->getRpgTable() === $this) {
                $rpgReservation->setRpgTable(null);
            }
        }

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
