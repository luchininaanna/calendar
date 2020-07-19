<?php


namespace App\Tests\Controller\JsonBuilder;


class MeetingParticipantJsonBuilder
{
    public function createMeetingParticipantJson(string $organizerId, string $meetingId, string $userId): array
    {
        return [
            "invokerId" => $organizerId,
	        "meetingId" => $meetingId,
	        "participantId" => $userId,
        ];
    }
}