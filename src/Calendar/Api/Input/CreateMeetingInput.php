<?php


namespace App\Calendar\Api\Input;


class CreateMeetingInput
{
    private string $organizerId;
    private string $name;
    private string $location;
    private \DateTime $startTime;

    /**
     * CreateMeetingInput constructor.
     * @param string $organizerId
     * @param string $name
     * @param string $location
     * @param \DateTime $startTime
     */
    public function __construct(string $organizerId, string $name, string $location, \DateTime $startTime)
    {
        $this->organizerId = $organizerId;
        $this->name = $name;
        $this->location = $location;
        $this->startTime = $startTime;
    }

    /**
     * @return string
     */
    public function getOrganizerId(): string
    {
        return $this->organizerId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @return \DateTime
     */
    public function getStartTime(): \DateTime
    {
        return $this->startTime;
    }
}