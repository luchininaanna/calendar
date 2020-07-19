<?php


namespace App\Calendar\Domain\Model;


class Meeting
{
    private string $id;
    private string $name;
    private string $location;
    private string $organizerId;
    private \DateTime $startTime;

    public function __construct(
        string $id,
        string $organizerId,
        string $name,
        string $location,
        \DateTime $startTime
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->location = $location;
        $this->startTime = $startTime;
        $this->organizerId = $organizerId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getOrganizerId(): string
    {
        return $this->organizerId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getStartTime(): \DateTime
    {
        return $this->startTime;
    }
}