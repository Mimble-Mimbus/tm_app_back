<?php

namespace App\Entity;

use App\Repository\TypePaymentableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypePaymentableRepository::class)]
class TypePaymentable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'typePaymentable', targetEntity: Paymentable::class)]
    private Collection $paymentables;

    public function __construct()
    {
        $this->paymentables = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Paymentable>
     */
    public function getPaymentables(): Collection
    {
        return $this->paymentables;
    }

    public function addPaymentable(Paymentable $paymentable): static
    {
        if (!$this->paymentables->contains($paymentable)) {
            $this->paymentables->add($paymentable);
            $paymentable->setTypePaymentable($this);
        }

        return $this;
    }

    public function removePaymentable(Paymentable $paymentable): static
    {
        if ($this->paymentables->removeElement($paymentable)) {
            // set the owning side to null (unless already changed)
            if ($paymentable->getTypePaymentable() === $this) {
                $paymentable->setTypePaymentable(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
