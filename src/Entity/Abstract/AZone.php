<?php

namespace App\Entity\Abstract;

use App\Entity\Event;
use App\Repository\Abstract\AZoneRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;

#[MappedSuperclass]
abstract class AZone
{
    #[ORM\Column(length: 255)]
    protected ?string $name = null;
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    abstract public function getEvent(): ?Event;
    abstract public function setEvent(?Event $event):static;
}
