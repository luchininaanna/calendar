<?php


namespace App\Controller\InputFactory;


use App\Calendar\Api\Input\InviteMeetingParticipantInput;

class InviteMeetingParticipantInputFactory
{
    public static function buildInput(string $request): ?InviteMeetingParticipantInput
    {
        $json = json_decode($request, true);
        if (empty($json['invokerId']) || empty($json['meetingId']) || empty($json['participantId']))
        {
            return null;
        }

        return new InviteMeetingParticipantInput($json['invokerId'], $json['meetingId'], $json['participantId']);
    }
}