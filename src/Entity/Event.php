<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startingDate = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $durationTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $registrationEnd = null;

    #[ORM\Column]
    private ?int $maxRegistration = null;

    #[ORM\Column(length: 1000)]
    private ?string $eventInformations = null;

    #[ORM\Column(length: 20)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'eventCreated')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $organiser = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'eventLived')]
    private Collection $participants;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Place $place = null;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
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

    public function getStartingDate(): ?\DateTimeInterface
    {
        return $this->startingDate;
    }

    public function setStartingDate(\DateTimeInterface $startingDate): static
    {
        $this->startingDate = $startingDate;

        return $this;
    }

    public function getDurationTime(): ?\DateTimeInterface
    {
        return $this->durationTime;
    }

    public function setDurationTime(\DateTimeInterface $durationTime): static
    {
        $this->durationTime = $durationTime;

        return $this;
    }

    public function getRegistrationEnd(): ?\DateTimeInterface
    {
        return $this->registrationEnd;
    }

    public function setRegistrationEnd(\DateTimeInterface $registrationEnd): static
    {
        $this->registrationEnd = $registrationEnd;

        return $this;
    }

    public function getMaxRegistration(): ?int
    {
        return $this->maxRegistration;
    }

    public function setMaxRegistration(int $maxRegistration): static
    {
        $this->maxRegistration = $maxRegistration;

        return $this;
    }

    public function getEventInformations(): ?string
    {
        return $this->eventInformations;
    }

    public function setEventInformations(string $eventInformations): static
    {
        $this->eventInformations = $eventInformations;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getOrganiser(): ?User
    {
        return $this->organiser;
    }

    public function setOrganiser(?User $organiser): static
    {
        $this->organiser = $organiser;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
        }

        return $this;
    }

    public function removeParticipant(User $participant): static
    {
        $this->participants->removeElement($participant);

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): static
    {
        $this->place = $place;

        return $this;
    }

}
