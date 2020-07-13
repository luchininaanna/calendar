<?php


namespace App\Calendar\Domain\Model;


class Meeting
{
    private string $uuid;
    private string $name;
    private string $location;
    private string $loggedUserId;
    private \DateTime $startTime;

    public function __construct(
        string $uuid,
        string $loggedUserId,
        string $name,
        string $location,
        \DateTime $startTime
    ) {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->location = $location;
        $this->startTime = $startTime;
        $this->loggedUserId = $loggedUserId;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getLoggedUserId(): string
    {
        return $this->loggedUserId;
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