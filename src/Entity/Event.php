<?php

namespace App\Entity;

use App\Entity\Abstract\APaymentable;
use App\Entity\Abstract\AStructure;
use App\Entity\Abstract\AZone;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event extends AStructure
{
    #[ORM\OneToMany(mappedBy: 'event', targetEntity: OpenDay::class, orphanRemoval: true)]
    private Collection $openDays;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: AZone::class, orphanRemoval: true)]
    private Collection $aZones;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?organization $organization = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: APaymentable::class, orphanRemoval: true)]
    private Collection $aPaymentable;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: VolunteerShift::class, orphanRemoval: true)]
    private Collection $volunteerShifts;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Reporting::class)]
    private Collection $reportings;

    public function __construct()
    {
        parent::__construct();
        $this->openDays = new ArrayCollection();
        $this->aZones = new ArrayCollection();
        $this->aPaymentable = new ArrayCollection();
        $this->volunteerShifts = new ArrayCollection();
        $this->reportings = new ArrayCollection();
    }

    /**
     * @return Collection<int, OpenDay>
     */
    public function getOpenDays(): Collection
    {
        return $this->openDays;
    }

    public function addOpenDay(OpenDay $openDay): static
    {
        if (!$this->openDays->contains($openDay)) {
            $this->openDays->add($openDay);
            $openDay->setEvent($this);
        }

        return $this;
    }

    public function removeOpenDay(OpenDay $openDay): static
    {
        if ($this->openDays->removeElement($openDay)) {
            // set the owning side to null (unless already changed)
            if ($openDay->getEvent() === $this) {
                $openDay->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AZone>
     */
    public function getAZones(): Collection
    {
        return $this->aZones;
    }

    public function addAZone(AZone $aZone): static
    {
        if (!$this->aZones->contains($aZone)) {
            $this->aZones->add($aZone);
            $aZone->setEvent($this);
        }

        return $this;
    }

    public function removeAZone(AZone $aZone): static
    {
        if ($this->aZones->removeElement($aZone)) {
            // set the owning side to null (unless already changed)
            if ($aZone->getEvent() === $this) {
                $aZone->setEvent(null);
            }
        }

        return $this;
    }

    public function getOrganization(): ?organization
    {
        return $this->organization;
    }

    public function setOrganization(?organization $organization): static
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * @return Collection<int, APaymentable>
     */
    public function getAPaymentable(): Collection
    {
        return $this->aPaymentable;
    }

    public function addAPaymentable(APaymentable $aPaymentable): static
    {
        if (!$this->aPaymentable->contains($aPaymentable)) {
            $this->aPaymentable->add($aPaymentable);
            $aPaymentable->setEvent($this);
        }

        return $this;
    }

    public function removeAPaymentable(APaymentable $aPaymentable): static
    {
        if ($this->aPaymentable->removeElement($aPaymentable)) {
            // set the owning side to null (unless already changed)
            if ($aPaymentable->getEvent() === $this) {
                $aPaymentable->setEvent(null);
            }
        }

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
            $volunteerShift->setEvent($this);
        }

        return $this;
    }

    public function removeVolunteerShift(VolunteerShift $volunteerShift): static
    {
        if ($this->volunteerShifts->removeElement($volunteerShift)) {
            // set the owning side to null (unless already changed)
            if ($volunteerShift->getEvent() === $this) {
                $volunteerShift->setEvent(null);
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
            $reporting->setEvent($this);
        }

        return $this;
    }

    public function removeReporting(Reporting $reporting): static
    {
        if ($this->reportings->removeElement($reporting)) {
            // set the owning side to null (unless already changed)
            if ($reporting->getEvent() === $this) {
                $reporting->setEvent(null);
            }
        }

        return $this;
    }
}
