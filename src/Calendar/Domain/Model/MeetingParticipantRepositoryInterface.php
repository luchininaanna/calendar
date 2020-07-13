<?php


namespace App\Calendar\Domain\Model;


interface MeetingParticipantRepositoryInterface
{
    public function deleteUserFromMeetings(string $userUuid): void;

    public function deleteInvitationByMeetingId(string $meetingUuid): void;

    public function deleteUserFromMeeting(string $userUuid, string $meetingUuid): void;

    public function createMeetingParticipant(MeetingParticipant $meetingParticipant): void;

    public function isUserIsMeetingParticipant(string $userUuid, string $meetingUuid): bool;

    public function isMeetingHasNotAcceptableNumberOfParticipants(string $meetingUuid): bool;
}