<?php

namespace App\EventListener;

use App\Entity\EntertainmentReservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EntertainmentReservationListener 
{   
    public function __construct(
      public EntityManagerInterface $em 
    ) {}

    public function prePersist (EntertainmentReservation $entertainmentReservation)
    {
        $entertainmentSchedule = $entertainmentReservation->getEntertainmentSchedule();

        if ($entertainmentSchedule) {
            $email = $entertainmentReservation->getEmail();
            foreach ($entertainmentSchedule->getActivityReservations() as $reservation) {
                if (($reservation !== $entertainmentReservation) && ($reservation->getEmail() === $email)) {
                    throw new HttpException(400, "reservation with email: ".$email." for entertainment: ".$entertainmentSchedule->getEntertainment()->getName()." already exist");
                }
            }
        }
    }
}