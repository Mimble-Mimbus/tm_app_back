<?php

namespace App\Entity;

use App\Entity\Abstract\AStructure;
use App\Entity\Zone;
use App\Entity\Paymentable;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event extends AStructure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: OpenDay::class, orphanRemoval: true)]
    private Collection $openDays;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Zone::class, orphanRemoval: true)]
    private Collection $zones;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Organization $organization = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Paymentable::class, orphanRemoval: true)]
    private Collection $paymentable;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: VolunteerShift::class, orphanRemoval: true)]
    private Collection $volunteerShifts;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Reporting::class)]
    private Collection $reportings;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Url::class)]
    private Collection $urls;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Guild::class)]
    private Collection $guilds;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Quest::class)]
    private Collection $quests;

    #[ORM\OneToOne(mappedBy: 'event', cascade: ['persist', 'remove'])]
    private ?RpgZone $rpgZone = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: RpgZone::class)]
    private Collection $rpgZones;

    public function __construct()
    {
        $this->openDays = new ArrayCollection();
        $this->zones = new ArrayCollection();
        $this->paymentable = new ArrayCollection();
        $this->volunteerShifts = new ArrayCollection();
        $this->reportings = new ArrayCollection();
        $this->urls = new ArrayCollection();
        $this->guilds = new ArrayCollection();
        $this->quests = new ArrayCollection();
        $this->rpgZones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection<int, Zone>
     */
    public function getZones(): Collection
    {
        return $this->zones;
    }

    public function addZone(Zone $zone): static
    {
        if (!$this->zones->contains($zone)) {
            $this->zones->add($zone);
            $zone->setEvent($this);
        }

        return $this;
    }

    public function removeZone(Zone $zone): static
    {
        if ($this->zones->removeElement($zone)) {
            // set the owning side to null (unless already changed)
            if ($zone->getEvent() === $this) {
                $zone->setEvent(null);
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
        return $this->paymentable;
    }

    public function addAPaymentable(Paymentable $paymentable): static
    {
        if (!$this->paymentable->contains($paymentable)) {
            $this->paymentable->add($paymentable);
            $paymentable->setEvent($this);
        }

        return $this;
    }

    public function removeAPaymentable(Paymentable $paymentable): static
    {
        if ($this->paymentable->removeElement($paymentable)) {
            // set the owning side to null (unless already changed)
            if ($paymentable->getEvent() === $this) {
                $paymentable->setEvent(null);
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

    /**
     * @return Collection<int, Url>
     */
    public function getUrls(): Collection
    {
        return $this->urls;
    }

    public function addUrl(Url $url): static
    {
        if (!$this->urls->contains($url)) {
            $this->urls->add($url);
            $url->setEvent($this);
        }

        return $this;
    }

    public function removeUrl(Url $url): static
    {
        if ($this->urls->removeElement($url)) {
            // set the owning side to null (unless already changed)
            if ($url->getEvent() === $this) {
                $url->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Guild>
     */
    public function getGuilds(): Collection
    {
        return $this->guilds;
    }

    public function addGuild(Guild $guild): static
    {
        if (!$this->guilds->contains($guild)) {
            $this->guilds->add($guild);
            $guild->setEvent($this);
        }

        return $this;
    }

    public function removeGuild(Guild $guild): static
    {
        if ($this->guilds->removeElement($guild)) {
            // set the owning side to null (unless already changed)
            if ($guild->getEvent() === $this) {
                $guild->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Quest>
     */
    public function getQuests(): Collection
    {
        return $this->quests;
    }

    public function addQuest(Quest $quest): static
    {
        if (!$this->quests->contains($quest)) {
            $this->quests->add($quest);
            $quest->setEvent($this);
        }

        return $this;
    }

    public function removeQuest(Quest $quest): static
    {
        if ($this->quests->removeElement($quest)) {
            // set the owning side to null (unless already changed)
            if ($quest->getEvent() === $this) {
                $quest->setEvent(null);
            }
        }

        return $this;
    }

    public function getRpgZone(): ?RpgZone
    {
        return $this->rpgZone;
    }

    public function setRpgZone(RpgZone $rpgZone): static
    {
        // set the owning side of the relation if necessary
        if ($rpgZone->getEvent() !== $this) {
            $rpgZone->setEvent($this);
        }

        $this->rpgZone = $rpgZone;

        return $this;
    }

    /**
     * @return Collection<int, RpgZone>
     */
    public function getRpgZones(): Collection
    {
        return $this->rpgZones;
    }

    public function addRpgZone(RpgZone $rpgZone): static
    {
        if (!$this->rpgZones->contains($rpgZone)) {
            $this->rpgZones->add($rpgZone);
            $rpgZone->setEvent($this);
        }

        return $this;
    }

    public function removeRpgZone(RpgZone $rpgZone): static
    {
        if ($this->rpgZones->removeElement($rpgZone)) {
            // set the owning side to null (unless already changed)
            if ($rpgZone->getEvent() === $this) {
                $rpgZone->setEvent(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
