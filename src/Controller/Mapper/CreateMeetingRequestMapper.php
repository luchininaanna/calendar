<?php


namespace App\Controller\Mapper;


use App\Calendar\Api\Input\CreateMeetingInput;

class CreateMeetingRequestMapper
{
    public static function buildInput(string $request): CreateMeetingInput
    {
        $json = json_decode($request, true);
        return new CreateMeetingInput($json['organizerId'], $json['name'], $json['location'], new \DateTime($json['startTime']));
    }
}