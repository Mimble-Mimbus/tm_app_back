<?php

namespace App\Entity\Abstract;

use App\Entity\Url;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;

#[MappedSuperclass]
abstract class AStructure
{

    #[ORM\Column(length: 255)]
    protected ?string $name = null;

    #[ORM\Column(type: 'text')]
    protected ?string $presentation = null;


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation($presentation)
    {
        $this->presentation = $presentation;
    }

    public function __toString()
    {
        return $this->name;
    }

    abstract public function getUrls(): Collection;

    abstract public function addUrl(Url $url): static;

    abstract public function removeUrl(Url $url): static;
}
