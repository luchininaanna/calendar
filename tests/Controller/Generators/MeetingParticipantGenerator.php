<?php


namespace App\Tests\Controller\Generators;


class MeetingParticipantGenerator
{
    public function createMeetingParticipant(string $organizerId, string $meetingId, string $userId): array
    {
        return [
            "loggedUserId" => $organizerId,
	        "meetingId" => $meetingId,
	        "participantId" => $userId
        ];
    }
}