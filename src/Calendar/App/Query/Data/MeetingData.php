<?php


namespace App\Calendar\App\Query\Data;


class MeetingData
{
    private string $uuid;
    private string $name;
    private string $location;
    private string $startTime;

    public function __construct(string $uuid, string $name, string $location, string $startTime)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->location = $location;
        $this->startTime = $startTime;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getStartTime(): string
    {
        return $this->startTime;
    }
}