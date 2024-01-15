<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $tag = null;

    #[ORM\ManyToMany(targetEntity: RpgActivity::class, mappedBy: 'tags')]
    private Collection $rpgActivities;

    #[ORM\ManyToMany(targetEntity: Rpg::class, mappedBy: 'tags')]
    private Collection $rpgs;

    public function __construct()
    {
        $this->rpgActivities = new ArrayCollection();
        $this->rpgs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(string $tag): static
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * @return Collection<int, RpgActivity>
     */
    public function getRpgActivities(): Collection
    {
        return $this->rpgActivities;
    }

    public function addRpgTable(RpgActivity $rpgActivity): static
    {
        if (!$this->rpgActivities->contains($rpgActivity)) {
            $this->rpgActivities->add($rpgActivity);
            $rpgActivity->addTag($this);
        }

        return $this;
    }

    public function removeRpgTable(RpgActivity $rpgActivity): static
    {
        if ($this->rpgActivities->removeElement($rpgActivity)) {
            $rpgActivity->removeTag($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Rpg>
     */
    public function getRpgs(): Collection
    {
        return $this->rpgs;
    }

    public function addRpg(Rpg $rpg): static
    {
        if (!$this->rpgs->contains($rpg)) {
            $this->rpgs->add($rpg);
            $rpg->addTag($this);
        }

        return $this;
    }

    public function removeRpg(Rpg $rpg): static
    {
        if ($this->rpgs->removeElement($rpg)) {
            $rpg->removeTag($this);
        }

        return $this;
    }
}
