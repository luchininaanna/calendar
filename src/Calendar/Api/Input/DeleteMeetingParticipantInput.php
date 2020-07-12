<?php


namespace App\Calendar\Api\Input;


class DeleteMeetingParticipantInput
{
    private string $meetingId;
    private string $loggedUserId;
    private string $participantId;

    public function __construct(string $loggedUserId, string $meetingId, string $participantId)
    {
        $this->meetingId = $meetingId;
        $this->loggedUserId = $loggedUserId;
        $this->participantId = $participantId;
    }

    public function getLoggedUserId(): string
    {
        return $this->loggedUserId;
    }

    public function getMeetingId(): string
    {
        return $this->meetingId;
    }

    public function getParticipantId(): string
    {
        return $this->participantId;
    }
}