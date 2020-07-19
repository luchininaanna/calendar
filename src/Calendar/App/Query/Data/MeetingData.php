<?php


namespace App\Calendar\App\Query\Data;


class MeetingData
{
    private string $id;
    private string $name;
    private string $location;
    private string $startTime;

    public function __construct(string $id, string $name, string $location, string $startTime)
    {
        $this->id = $id;
        $this->name = $name;
        $this->location = $location;
        $this->startTime = $startTime;
    }

    public function getId(): string
    {
        return $this->id;
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