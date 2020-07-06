<?php


namespace App\Calendar\App\Command;


class CreateInvitationCommand
{
    private string $loggedUserId;
    private string $meetingId;
    private string $participantId;

    public function __construct(string $loggedUserId, string $meetingId, string $participantId)
    {
        $this->loggedUserId = $loggedUserId;
        $this->meetingId = $meetingId;
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