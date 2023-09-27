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

    #[ORM\ManyToMany(targetEntity: RpgTable::class, mappedBy: 'tags')]
    private Collection $rpgTables;

    #[ORM\ManyToMany(targetEntity: Rpg::class, mappedBy: 'tags')]
    private Collection $rpgs;

    public function __construct()
    {
        $this->rpgTables = new ArrayCollection();
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
     * @return Collection<int, RpgTable>
     */
    public function getRpgTables(): Collection
    {
        return $this->rpgTables;
    }

    public function addRpgTable(RpgTable $rpgTable): static
    {
        if (!$this->rpgTables->contains($rpgTable)) {
            $this->rpgTables->add($rpgTable);
            $rpgTable->addTag($this);
        }

        return $this;
    }

    public function removeRpgTable(RpgTable $rpgTable): static
    {
        if ($this->rpgTables->removeElement($rpgTable)) {
            $rpgTable->removeTag($this);
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
