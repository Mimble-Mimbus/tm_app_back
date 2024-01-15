<?php

namespace App\Entity;

use App\Entity\Abstract\AUser;
use App\Repository\UserTMRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserTMRepository::class)]
class UserTM extends AUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: EntertainmentReservation::class)]
    private Collection $entertainmentReservations;

    #[ORM\OneToMany(mappedBy: 'userGm', targetEntity: RpgActivity::class)]
    private Collection $rpgActivities;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: RpgReservation::class)]
    private Collection $rpgReservations;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?UserParams $userParams = null;

    #[ORM\ManyToOne(inversedBy: 'userTMs')]
    private ?Guild $guild = null;

    public function __construct()
    {
        parent::__construct();
        $this->entertainmentReservations = new ArrayCollection();
        $this->rpgActivities = new ArrayCollection();
        $this->rpgReservations = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, EntertainmentReservation>
     */
    public function getEntertainmentReservations(): Collection
    {
        return $this->entertainmentReservations;
    }

    public function addEntertainmentReservation(EntertainmentReservation $entertainmentReservation): static
    {
        if (!$this->entertainmentReservations->contains($entertainmentReservation)) {
            $this->entertainmentReservations->add($entertainmentReservation);
            $entertainmentReservation->setUser($this);
        }

        return $this;
    }

    public function removeEntertainmentReservation(EntertainmentReservation $entertainmentReservation): static
    {
        if ($this->entertainmentReservations->removeElement($entertainmentReservation)) {
            // set the owning side to null (unless already changed)
            if ($entertainmentReservation->getUser() === $this) {
                $entertainmentReservation->setUser(null);
            }
        }

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
            $rpgActivity->setUserGm($this);
        }

        return $this;
    }

    public function removeRpgActivity(RpgActivity $rpgActivity): static
    {
        if ($this->rpgActivities->removeElement($rpgActivity)) {
            // set the owning side to null (unless already changed)
            if ($rpgActivity->getUserGm() === $this) {
                $rpgActivity->setUserGm(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RpgReservation>
     */
    public function getRpgReservations(): Collection
    {
        return $this->rpgReservations;
    }

    public function addRpgReservation(RpgReservation $rpgReservation): static
    {
        if (!$this->rpgReservations->contains($rpgReservation)) {
            $this->rpgReservations->add($rpgReservation);
            $rpgReservation->setUser($this);
        }

        return $this;
    }

    public function removeRpgReservation(RpgReservation $rpgReservation): static
    {
        if ($this->rpgReservations->removeElement($rpgReservation)) {
            // set the owning side to null (unless already changed)
            if ($rpgReservation->getUser() === $this) {
                $rpgReservation->setUser(null);
            }
        }

        return $this;
    }

    public function getUserParams(): ?UserParams
    {
        return $this->userParams;
    }

    public function setUserParams(UserParams $userParams): static
    {
        // set the owning side of the relation if necessary
        if ($userParams->getUser() !== $this) {
            $userParams->setUser($this);
        }

        $this->userParams = $userParams;

        return $this;
    }

    public function getGuild(): ?Guild
    {
        return $this->guild;
    }

    public function setGuild(?Guild $guild): static
    {
        $this->guild = $guild;

        return $this;
    }
}
