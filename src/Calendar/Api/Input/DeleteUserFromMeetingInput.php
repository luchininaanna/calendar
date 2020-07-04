<?php


namespace App\Calendar\Api\Input;


class DeleteUserFromMeetingInput
{
    private string $organizerId;
    private string $meetingId;
    private string $participantId;

    public function __construct(string $organizerId, string $meetingId, string $participantId)
    {
        $this->organizerId = $organizerId;
        $this->meetingId = $meetingId;
        $this->participantId = $participantId;
    }

    public function getOrganizerId(): string
    {
        return $this->organizerId;
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