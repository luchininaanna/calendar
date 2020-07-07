<?php


namespace App\Controller\Mapper;


class GetMeetingsRequestMapper
{
    public static function buildInput(string $request): ?String
    {
        $json = json_decode($request, true);
        if (empty($json['loggedUserId']))
        {
            return null;
        }

        return $json['loggedUserId'];
    }
}