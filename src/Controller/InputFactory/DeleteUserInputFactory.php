<?php


namespace App\Controller\InputFactory;


use App\Calendar\Api\Input\DeleteUserInput;

class DeleteUserInputFactory
{
    public static function buildInput(string $request): ?DeleteUserInput
    {
        $json = json_decode($request, true);
        if (empty($json['userId']))
        {
            return null;
        }

        return new DeleteUserInput($json['userId']);
    }
}