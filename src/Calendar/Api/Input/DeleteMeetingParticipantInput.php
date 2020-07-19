<?php


namespace App\Calendar\Api\Input;


class DeleteMeetingParticipantInput
{
    private string $meetingId;
    private string $invokerId;
    private string $participantId;

    public function __construct(string $invokerId, string $meetingId, string $participantId)
    {
        $this->meetingId = $meetingId;
        $this->invokerId = $invokerId;
        $this->participantId = $participantId;
    }

    public function getInvokerId(): string
    {
        return $this->invokerId;
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