<?php

namespace App\Entity\Abstract;

use App\Entity\Reporting;
use App\Entity\VolunteerShift;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[MappedSuperclass]
abstract class AUser implements UserInterface, PasswordAuthenticatedUserInterface
{

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(length: 15)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: VolunteerShift::class, orphanRemoval: true)]
    private Collection $volunteerShifts;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Reporting::class)]
    private Collection $reportings;

    public function __construct()
    {
        $this->volunteerShifts = new ArrayCollection();
        $this->reportings = new ArrayCollection();
    }


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
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
     * @return Collection<int, VolunteerShift>
     */
    public function getVolunteerShifts(): Collection
    {
        return $this->volunteerShifts;
    }

    public function addVolunteerShift(VolunteerShift $volunteerShift): static
    {
        if (!$this->volunteerShifts->contains($volunteerShift)) {
            $this->volunteerShifts->add($volunteerShift);
            $volunteerShift->setUser($this);
        }

        return $this;
    }

    public function removeVolunteerShift(VolunteerShift $volunteerShift): static
    {
        if ($this->volunteerShifts->removeElement($volunteerShift)) {
            // set the owning side to null (unless already changed)
            if ($volunteerShift->getUser() === $this) {
                $volunteerShift->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reporting>
     */
    public function getReportings(): Collection
    {
        return $this->reportings;
    }

    public function addReporting(Reporting $reporting): static
    {
        if (!$this->reportings->contains($reporting)) {
            $this->reportings->add($reporting);
            $reporting->setUser($this);
        }

        return $this;
    }

    public function removeReporting(Reporting $reporting): static
    {
        if ($this->reportings->removeElement($reporting)) {
            // set the owning side to null (unless already changed)
            if ($reporting->getUser() === $this) {
                $reporting->setUser(null);
            }
        }

        return $this;
    }
}
