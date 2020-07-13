<?php


namespace App\Calendar\Domain\Model;


class MeetingParticipant
{
    private string $userUuid;
    private string $meetingUuid;

    public function __construct(string $meetingUuid, string $userUuid)
    {
        $this->userUuid = $userUuid;
        $this->meetingUuid = $meetingUuid;
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