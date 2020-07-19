<?php


namespace App\Calendar\App\Command;


class CreateMeetingCommand
{
    private string $name;
    private string $location;
    private \DateTime $startTime;
    private string $invokerId;

    public function __construct(string $invokerId, string $name, string $location, \DateTime $startTime)
    {
        $this->name = $name;
        $this->location = $location;
        $this->startTime = $startTime;
        $this->invokerId = $invokerId;
    }

    public function getInvokerId(): string
    {
        return $this->invokerId;
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