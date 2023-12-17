<?php

namespace App\Entity;

use App\Entity\Abstract\AActivityReservation;
use App\Repository\EntertainmentReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntertainmentReservationRepository::class)]
class EntertainmentReservation extends AActivityReservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'entertainmentReservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EntertainmentSchedule $entertainmentSchedule = null;

    #[ORM\ManyToOne(inversedBy: 'entertainmentReservations')]
    #[ORM\JoinColumn(nullable: true)]
    private ?UserTM $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntertainmentSchedule(): ?EntertainmentSchedule
    {
        return $this->getActivitySchedule();
    }

    public function setEntertainmentSchedule(EntertainmentSchedule $entertainmentSchedule): static
    {
        return $this->setActivitySchedule($entertainmentSchedule);
    }

    public function getActivitySchedule(): ?EntertainmentSchedule
    {
        return $this->entertainmentSchedule;
    }

    public function setActivitySchedule($entertainmentSchedule): static
    {
        $this->entertainmentSchedule = $entertainmentSchedule;

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
