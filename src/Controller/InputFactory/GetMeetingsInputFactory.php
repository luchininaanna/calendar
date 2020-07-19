<?php


namespace App\Controller\InputFactory;


class GetMeetingsInputFactory
{
    public static function buildInput(string $request): ?string
    {
        $json = json_decode($request, true);
        if (empty($json['invokerId']))
        {
            return null;
        }

        return $json['invokerId'];
    }
}