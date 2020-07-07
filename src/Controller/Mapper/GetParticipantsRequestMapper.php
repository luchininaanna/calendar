<?php


namespace App\Controller\Mapper;


use App\Calendar\Api\Input\GetParticipantInput;

class GetParticipantsRequestMapper
{
    public static function buildInput(string $request): ?GetParticipantInput
    {
        $json = json_decode($request, true);
        if (empty($json['loggedUserId']) || empty($json['meetingId']))
        {
            return null;
        }

        return new GetParticipantInput($json['loggedUserId'], $json['meetingId']);
    }
}