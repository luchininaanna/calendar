<?php


namespace App\Calendar\Domain\Model;


interface MeetingParticipantRepositoryInterface
{
    public function getMeetingParticipantAmount(string $meetingId): int;

    public function deleteParticipantFromAllMeetings(string $userId): void;

    public function deleteAllMeetingParticipants(string $meetingId): void;

    public function isMeetingParticipant(string $userId, string $meetingId): bool;

    public function deleteMeetingParticipant(string $userId, string $meetingId): void;

    public function createMeetingParticipant(MeetingParticipant $meetingParticipant): void;
}