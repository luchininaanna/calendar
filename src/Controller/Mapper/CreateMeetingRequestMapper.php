<?php


namespace App\Controller\Mapper;


use App\Calendar\Api\Input\CreateMeetingInput;

class CreateMeetingRequestMapper
{
    public static function buildInput(string $request): ?CreateMeetingInput
    {
        $json = json_decode($request, true);
        if (empty($json['loggedUserId']) || empty($json['name']) || empty($json['location']) || empty($json['startTime']))
        {
            return null;
        }

        return new CreateMeetingInput($json['loggedUserId'], $json['name'], $json['location'],
            new \DateTime($json['startTime']));
    }
}