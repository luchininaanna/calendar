<?php


namespace App\Calendar\App\Command;


class DeleteMeetingCommand
{
    private string $organizerId;
    private string $meetingId;

    public function __construct(string $organizerId, string $meetingId)
    {
        $this->organizerId = $organizerId;
        $this->meetingId = $meetingId;
    }

    public function getOrganizerId(): string
    {
        return $this->organizerId;
    }

    public function getMeetingId(): string
    {
        return $this->meetingId;
    }
}