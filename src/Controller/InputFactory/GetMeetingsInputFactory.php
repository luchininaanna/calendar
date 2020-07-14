<?php


namespace App\Controller\InputFactory;


class GetMeetingsInputFactory
{
    public static function buildInput(string $request): ?string
    {
        $json = json_decode($request, true);
        if (empty($json['loggedUserId']))
        {
            return null;
        }

        return $json['loggedUserId'];
    }
}