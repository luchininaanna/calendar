<?php


namespace App\Calendar\App\Command;


class CreateMeetingCommand
{
    private string $name;
    private string $location;
    private \DateTime $startTime;
    private string $loggedUserId;

    public function __construct(string $loggedUserId, string $name, string $location, \DateTime $startTime)
    {
        $this->name = $name;
        $this->location = $location;
        $this->startTime = $startTime;
        $this->loggedUserId = $loggedUserId;
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