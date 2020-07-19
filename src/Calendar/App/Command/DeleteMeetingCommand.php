<?php


namespace App\Calendar\App\Command;


class DeleteMeetingCommand
{
    private string $meetingId;
    private string $invokerId;

    public function __construct(string $invokerId, string $meetingId)
    {
        $this->meetingId = $meetingId;
        $this->invokerId = $invokerId;
    }

    public function getInvokerId(): string
    {
        return $this->invokerId;
    }

    public function getMeetingId(): string
    {
        return $this->meetingId;
    }
}