<?php


namespace App\Controller\InputFactory;


use App\Calendar\Api\Input\DeleteMeetingInput;

class DeleteMeetingInputFactory
{
    public static function buildInput(string $request): ?DeleteMeetingInput
    {
        $json = json_decode($request, true);
        if (empty($json['invokerId']) || empty($json['meetingId']))
        {
            return null;
        }

        return new DeleteMeetingInput($json['invokerId'], $json['meetingId']);
    }
}