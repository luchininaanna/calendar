<?php


namespace App\Calendar\Domain\Model;


class MeetingParticipant
{
    private string $uuid;
    private string $organizerUuid;
    private string $meetingUuid;
    private string $userUuid;

    public function __construct(string $uuid, string $organizerUuid, string $meetingUuid, string $userUuid)
    {
        $this->uuid = $uuid;
        $this->organizerUuid = $organizerUuid;
        $this->meetingUuid = $meetingUuid;
        $this->userUuid = $userUuid;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getOrganizerUuid(): string
    {
        return $this->organizerUuid;
    }

    public function getMeetingUuid(): string
    {
        return $this->meetingUuid;
    }

    public function getUserUuid(): string
    {
        return $this->userUuid;
    }
}