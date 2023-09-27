<?php

namespace App\Entity;

use App\Repository\TriggerWarningRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TriggerWarningRepository::class)]
class TriggerWarning
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $theme = null;

    #[ORM\ManyToMany(targetEntity: RpgTable::class, mappedBy: 'triggerWarnings')]
    private Collection $rpgTables;

    #[ORM\ManyToMany(targetEntity: Rpg::class, mappedBy: 'triggerWarnings')]
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

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): static
    {
        $this->theme = $theme;

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
            $rpgTable->addTriggerWarning($this);
        }

        return $this;
    }

    public function removeRpgTable(RpgTable $rpgTable): static
    {
        if ($this->rpgTables->removeElement($rpgTable)) {
            $rpgTable->removeTriggerWarning($this);
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
            $rpg->addTriggerWarning($this);
        }

        return $this;
    }

    public function removeRpg(Rpg $rpg): static
    {
        if ($this->rpgs->removeElement($rpg)) {
            $rpg->removeTriggerWarning($this);
        }

        return $this;
    }
}
