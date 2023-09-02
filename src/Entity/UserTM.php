<?php

namespace App\Entity;

use App\Entity\Abstract\AUser;
use App\Repository\UserTMRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserTMRepository::class)]
class UserTM extends AUser
{
   
}
