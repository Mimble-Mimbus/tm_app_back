<?php

namespace App\Entity;

use App\Entity\Paymentable;
use App\Repository\PriceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PriceRepository::class)]
class Price
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $priceCondition = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\ManyToOne(inversedBy: 'prices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Paymentable $paymentable = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPriceCondition(): ?string
    {
        return $this->priceCondition;
    }

    public function setPriceCondition(?string $priceCondition): static
    {
        $this->priceCondition = $priceCondition;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getAPaymentable(): ?Paymentable
    {
        return $this->paymentable;
    }

    public function setAPaymentable(?Paymentable $paymentable): static
    {
        $this->paymentable = $paymentable;

        return $this;
    }
}
