<?php

namespace App\Entity;

use App\Entity\Event;
use App\Entity\Price;
use App\Repository\PaymentableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentableRepository::class)]
class Paymentable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'paymentable', targetEntity: Price::class, orphanRemoval:true, cascade: ['persist'])]
    private Collection $prices;

    #[ORM\ManyToOne(inversedBy: 'paymentable')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\ManyToOne(inversedBy: 'paymentables')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypePaymentable $typePaymentable = null;

    public function __construct()
    {
        $this->prices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }


    /**
     * @return Collection<int, Price>
     */
    public function getPrices(): Collection
    {
        return $this->prices;
    }

    public function addPrice(Price $price): static
    {
        if (!$this->prices->contains($price)) {
            $this->prices->add($price);
            $price->setPaymentable($this);
        }

        return $this;
    }

    public function removePrice(Price $price): static
    {
        if ($this->prices->removeElement($price)) {
            // set the owning side to null (unless already changed)
            if ($price->getPaymentable() === $this) {
                $price->setPaymentable(null);
            }
        }

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): static
    {
        $this->event = $event;

        return $this;
    }

    public function getTypePaymentable(): ?TypePaymentable
    {
        return $this->typePaymentable;
    }

    public function setTypePaymentable(?TypePaymentable $typePaymentable): static
    {
        $this->typePaymentable = $typePaymentable;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
