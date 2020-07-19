<?php


namespace App\Calendar\Domain\Model;


class MeetingParticipant
{
    private string $meetingId;
    private string $participantId;

    public function __construct(string $meetingId, string $participantId)
    {
        $this->meetingId = $meetingId;
        $this->participantId = $participantId;
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