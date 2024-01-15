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

    #[ORM\OneToMany(mappedBy: 'rpgTable', targetEntity: RpgReservation::class, cascade: ['remove'])]
    private Collection $rpgReservations;

    

    public function __construct()
    {
        $this->rpgReservations = new ArrayCollection();
        
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

    public function __toString() {
        return date_format($this->getStart(), 'd/m/Y H:i');
    }
}
