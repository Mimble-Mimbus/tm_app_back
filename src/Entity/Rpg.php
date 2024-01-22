<?php

namespace App\Entity;

use App\Repository\RpgRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RpgRepository::class)]
class Rpg
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $publisher = null;

    #[ORM\Column(length: 255)]
    private ?string $universe = null;

    #[ORM\OneToMany(mappedBy: 'rpg', targetEntity: RpgActivity::class)]
    private Collection $rpgActivities;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'rpgs')]
    private Collection $tags;

    #[ORM\ManyToMany(targetEntity: TriggerWarning::class, inversedBy: 'rpgs')]
    private Collection $triggerWarnings;

    public function __construct()
    {
        $this->rpgActivities = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->triggerWarnings = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPublisher(): ?string
    {
        return $this->publisher;
    }

    public function setPublisher(string $publisher): static
    {
        $this->publisher = $publisher;

        return $this;
    }

    public function getUniverse(): ?string
    {
        return $this->universe;
    }

    public function setUniverse(string $universe): static
    {
        $this->universe = $universe;

        return $this;
    }

    /**
     * @return Collection<int, RpgActivity>
     */
    public function getRpgActivities(): Collection
    {
        return $this->rpgActivities;
    }

    public function addRpgActivity(RpgActivity $rpgActivity): static
    {
        if (!$this->rpgActivities->contains($rpgActivity)) {
            $this->rpgActivities->add($rpgActivity);
            $rpgActivity->setRpg($this);
        }

        return $this;
    }

    public function removeRpgActivity(RpgActivity $rpgActivity): static
    {
        if ($this->rpgActivities->removeElement($rpgActivity)) {
            // set the owning side to null (unless already changed)
            if ($rpgActivity->getRpg() === $this) {
                $rpgActivity->setRpg(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * @return Collection<int, TriggerWarning>
     */
    public function getTriggerWarnings(): Collection
    {
        return $this->triggerWarnings;
    }

    public function addTriggerWarning(TriggerWarning $triggerWarning): static
    {
        if (!$this->triggerWarnings->contains($triggerWarning)) {
            $this->triggerWarnings->add($triggerWarning);
        }

        return $this;
    }

    public function removeTriggerWarning(TriggerWarning $triggerWarning): static
    {
        $this->triggerWarnings->removeElement($triggerWarning);

        return $this;
    }

    public function __toString() {
        return $this->name;
    }
}
