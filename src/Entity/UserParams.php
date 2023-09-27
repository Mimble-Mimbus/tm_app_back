<?php

namespace App\Entity;

use App\Repository\UserParamsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserParamsRepository::class)]
class UserParams
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $allNotifications = null;

    #[ORM\OneToOne(inversedBy: 'userParams', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserTM $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isAllNotifications(): ?bool
    {
        return $this->allNotifications;
    }

    public function setAllNotifications(bool $allNotifications): static
    {
        $this->allNotifications = $allNotifications;

        return $this;
    }

    public function getUser(): ?UserTM
    {
        return $this->user;
    }

    public function setUser(UserTM $user): static
    {
        $this->user = $user;

        return $this;
    }
}
