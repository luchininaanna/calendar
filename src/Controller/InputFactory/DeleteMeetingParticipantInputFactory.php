<?php


namespace App\Controller\InputFactory;


use App\Calendar\Api\Input\DeleteMeetingParticipantInput;

class DeleteMeetingParticipantInputFactory
{
    public static function buildInput(string $request): ?DeleteMeetingParticipantInput
    {
        $json = json_decode($request, true);
        if (empty($json['invokerId']) || empty($json['meetingId']) || empty($json['participantId']))
        {
            return null;
        }

        return new DeleteMeetingParticipantInput($json['invokerId'], $json['meetingId'], $json['participantId']);
    }
}