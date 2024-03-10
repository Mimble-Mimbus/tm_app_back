<?php

namespace App\Entity\Abstract;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Symfony\Component\Validator\Constraints as Assert;

#[MappedSuperclass]
abstract class AActivityReservation
{
    
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Assert\Email(message: "The email {{ value }} is not a valid email.")]
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[Assert\Regex(pattern: "/^\+31\(0\)[0-9]*$/", message: "invalide phone number {{ value }}")]
    #[ORM\Column(length: 255)]
    private ?string $phoneNumber = null;

    #[ORM\Column]
    private ?int $bookings = null;


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getBookings(): ?int
    {
        return $this->bookings;
    }

    public function setBookings(int $bookings): static
    {
        $this->bookings = $bookings;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
    
    abstract public function getUser();

    abstract public function setUser($user): static;

    abstract public function getActivitySchedule();
    
    abstract public function setActivitySchedule($activitySchedule): static;
    
}
