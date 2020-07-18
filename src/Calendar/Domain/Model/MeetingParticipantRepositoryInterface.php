<?php


namespace App\Calendar\Domain\Model;


interface MeetingParticipantRepositoryInterface
{
    public function deleteParticipantFromAllMeetings(string $userUuid): void;

    public function deleteAllMeetingParticipants(string $meetingUuid): void;

    public function deleteMeetingParticipant(string $userUuid, string $meetingUuid): void;

    public function createMeetingParticipant(MeetingParticipant $meetingParticipant): void;

    public function isMeetingParticipant(string $userUuid, string $meetingUuid): bool;

    public function isMeetingHasNotAcceptableNumberOfParticipants(string $meetingUuid): bool;
}