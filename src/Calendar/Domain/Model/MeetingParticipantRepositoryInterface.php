<?php


namespace App\Calendar\Domain\Model;


interface MeetingParticipantRepositoryInterface
{
    public function createMeetingParticipant(MeetingParticipant $meetingParticipant): void;
}