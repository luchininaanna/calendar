<?php


namespace App\Calendar\App\Query\Data;


class MeetingData
{
    private string $uuid;
    private string $name;
    private string $location;
    private string $start_time;

    public function __construct(string $uuid, string $name, string $location, string $start_time)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->location = $location;
        $this->start_time = $start_time;
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
        return $this->start_time;
    }


}