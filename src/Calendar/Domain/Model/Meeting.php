<?php


namespace App\Calendar\Domain\Model;


class Meeting
{
    private string $uuid;
    private string $loggedUserId;
    private string $name;
    private string $location;
    private \DateTime $startTime;

    public function __construct(string $uuid, string $loggedUserId, string $name, string $location, \DateTime $startTime)
    {
        $this->uuid = $uuid;
        $this->loggedUserId = $loggedUserId;
        $this->name = $name;
        $this->location = $location;
        $this->startTime = $startTime;
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