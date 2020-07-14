<?php


namespace App\Controller\InputFactory;


use App\Calendar\Api\Input\CreateMeetingParticipantInput;

class CreateInviteInputFactory
{
    public static function buildInput(string $request): ?CreateMeetingParticipantInput
    {
        $json = json_decode($request, true);
        if (empty($json['loggedUserId']) || empty($json['meetingId']) || empty($json['participantId']))
        {
            return null;
        }

        return new CreateMeetingParticipantInput($json['loggedUserId'], $json['meetingId'], $json['participantId']);
    }
}