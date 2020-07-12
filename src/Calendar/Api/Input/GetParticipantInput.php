<?php


namespace App\Calendar\Api\Input;


class GetParticipantInput
{
    private string $meetingId;
    private string $loggedUserId;

    public function __construct(string $loggedUserId, string $meetingId)
    {
        $this->meetingId = $meetingId;
        $this->loggedUserId = $loggedUserId;
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