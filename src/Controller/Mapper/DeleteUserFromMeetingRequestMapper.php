<?php


namespace App\Controller\Mapper;


use App\Calendar\Api\Input\DeleteUserFromMeetingInput;

class DeleteUserFromMeetingRequestMapper
{
    public static function buildInput(string $request): ?DeleteUserFromMeetingInput
    {
        $json = json_decode($request, true);
        if (empty($json['organizerId']) || empty($json['meetingId']) || empty($json['participantId']))
        {
            return null;
        }

        return new DeleteUserFromMeetingInput($json['organizerId'], $json['meetingId'], $json['participantId']);
    }
}