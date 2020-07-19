<?php


namespace App\Calendar\Domain\Model;


interface MeetingRepositoryInterface
{
    public function createMeeting(Meeting $meeting): void;

    public function isMeetingExist(string $meetingUuid): bool;

    public function deleteMeetingById(string $meetingUuid): void;

    public function deleteMeetingsForOrganizer(string $organizerUuid): void;

    public function isMeetingOrganizer(string $organizerUuid, string $meetingUuid): bool;
}