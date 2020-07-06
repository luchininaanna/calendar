<?php


namespace App\Controller\Mapper;


use App\Calendar\Api\Input\DeleteUserFromMeetingInput;

class DeleteUserFromMeetingRequestMapper
{
    public static function buildInput(string $request): ?DeleteUserFromMeetingInput
    {
        $json = json_decode($request, true);
        if (empty($json['loggedUserId']) || empty($json['meetingId']) || empty($json['participantId']))
        {
            return null;
        }

        return new DeleteUserFromMeetingInput($json['loggedUserId'], $json['meetingId'], $json['participantId']);
    }
}