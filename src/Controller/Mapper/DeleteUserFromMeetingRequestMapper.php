<?php


namespace App\Controller\Mapper;


use App\Calendar\Api\Input\DeleteUserFromMeetingInput;

class DeleteUserFromMeetingRequestMapper
{
    public static function buildInput(string $request): DeleteUserFromMeetingInput
    {
        $json = json_decode($request, true);
        //проверка, что строки не пустые
        return new DeleteUserFromMeetingInput($json['organizerId'], $json['meetingId'], $json['participantId']);
    }
}