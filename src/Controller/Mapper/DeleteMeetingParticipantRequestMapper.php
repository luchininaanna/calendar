<?php


namespace App\Controller\Mapper;


use App\Calendar\Api\Input\DeleteMeetingParticipantInput;

class DeleteMeetingParticipantRequestMapper
{
    public static function buildInput(string $request): ?DeleteMeetingParticipantInput
    {
        $json = json_decode($request, true);
        if (empty($json['loggedUserId']) || empty($json['meetingId']) || empty($json['participantId']))
        {
            return null;
        }

        return new DeleteMeetingParticipantInput($json['loggedUserId'], $json['meetingId'], $json['participantId']);
    }
}