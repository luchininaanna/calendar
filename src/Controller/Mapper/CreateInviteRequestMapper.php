<?php


namespace App\Controller\Mapper;


use App\Calendar\Api\Input\CreateInvitationInput;

class CreateInviteRequestMapper
{
    public static function buildInput(string $request): CreateInvitationInput
    {
        $json = json_decode($request, true);
        //проверка, что строки не пустые
        return new CreateInvitationInput($json['organizerId'], $json['meetingId'], $json['participantId']);
    }
}