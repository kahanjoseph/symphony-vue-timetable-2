<?php

namespace App\Entity;

use App\Repository\TimeEntryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimeEntryRepository::class)]
class TimeEntry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?\DateTime $clockIn = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?\DateTime $clockOut = null;

    #[ORM\ManyToOne(inversedBy: 'timeEntries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $userId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClockIn(): ?\DateTime
    {
        return $this->clockIn;
    }

    public function setClockIn(\DateTime $clockIn): static
    {
        $this->clockIn = $clockIn;

        return $this;
    }

    public function getClockOut(): ?\DateTime
    {
        return $this->clockOut;
    }

    public function setClockOut(?\DateTime $clockOut): static
    {
        $this->clockOut = $clockOut;

        return $this;
    }

    public function getUserId(): ?user
    {
        return $this->userId;
    }

    public function setUserId(?user $userId): static
    {
        $this->userId = $userId;

        return $this;
    }
}
