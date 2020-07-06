<?php


namespace App\Controller\Mapper;


use App\Calendar\Api\Input\DeleteMeetingInput;

class DeleteMeetingRequestMapper
{
    public static function buildInput(string $request): ?DeleteMeetingInput
    {
        $json = json_decode($request, true);
        if (empty($json['organizerId']) || empty($json['meetingId']))
        {
            return null;
        }

        return new DeleteMeetingInput($json['organizerId'], $json['meetingId']);
    }
}