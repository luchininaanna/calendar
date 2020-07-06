<?php


namespace App\Calendar\Domain\Model;


class MeetingParticipant
{
    private string $meetingUuid;
    private string $userUuid;

    public function __construct(string $meetingUuid, string $userUuid)
    {
        $this->meetingUuid = $meetingUuid;
        $this->userUuid = $userUuid;
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