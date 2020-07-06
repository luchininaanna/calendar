<?php


namespace App\Controller\Mapper;


use App\Calendar\Api\Input\CreateInvitationInput;

class CreateInviteRequestMapper
{
    public static function buildInput(string $request): ?CreateInvitationInput
    {
        $json = json_decode($request, true);
        if (empty($json['organizerId']) || empty($json['meetingId']) || empty($json['participantId']))
        {
            return null;
        }

        return new CreateInvitationInput($json['organizerId'], $json['meetingId'], $json['participantId']);
    }
}