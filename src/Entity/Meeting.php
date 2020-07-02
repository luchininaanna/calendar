<?php

namespace App\Entity;

use App\Repository\MeetingRepository;
use Cassandra\Date;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MeetingRepository::class)
 */
class Meeting
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="binary")
     */
    private $uuid;

    /**
     * @ORM\Column(type="binary")
     */
    private $organizer_uuid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start_time;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function setUuid($uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getOrganizerUuid()
    {
        return $this->organizer_uuid;
    }

    public function setOrganizerUuid($organizer_uuid): self
    {
        $this->organizer_uuid = $organizer_uuid;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getStartTime(): ?\DateTime
    {
        return $this->start_time;
    }

    public function setStartTime(\DateTime $start_begin): self
    {
        $this->start_time = $start_begin;

        return $this;
    }
}
