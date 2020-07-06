<?php


namespace App\Calendar\App\Command;


class DeleteMeetingCommand
{
    private string $loggedUserId;
    private string $meetingId;

    public function __construct(string $loggedUserId, string $meetingId)
    {
        $this->loggedUserId = $loggedUserId;
        $this->meetingId = $meetingId;
    }

    public function getLoggedUserId(): string
    {
        return $this->loggedUserId;
    }

    public function getMeetingId(): string
    {
        return $this->meetingId;
    }
}