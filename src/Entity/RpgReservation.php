<?php

namespace App\Entity;

use App\Entity\Abstract\AActivityReservation;
use App\Repository\RpgReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RpgReservationRepository::class)]
class RpgReservation extends AActivityReservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'rpgReservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RpgTable $rpgTable = null;

    #[ORM\ManyToOne(inversedBy: 'rpgReservations')]
    #[ORM\JoinColumn(nullable: true)]
    private ?UserTM $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getRpgTable()
    {
        return $this->getActivitySchedule();
    }
    
    public function setRpgTable(RpgTable $rpgTable)
    {
        $this->setActivitySchedule($rpgTable);
    }
    
    public function getActivitySchedule(): ?RpgTable
    {
        return $this->rpgTable;
    }

    public function setActivitySchedule($rpgTable): static
    {
        $this->rpgTable = $rpgTable;

        return $this;
    }

    public function getUser(): ?UserTM
    {
        return $this->user;
    }

    public function setUser($user): static
    {
        $this->user = $user;

        return $this;
    }
}
