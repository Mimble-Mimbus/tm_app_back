<?php

namespace App\Entity;

use App\Entity\Abstract\AUser;
use App\Repository\UserTMRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserTMRepository::class)]
class UserTM extends AUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    public function getId(): ?int
    {
        return $this->id;
    }
}
