<?php


namespace App\Controller\Mapper;


use App\Calendar\Api\Input\DeleteUserInput;

class DeleteUserRequestMapper
{
    public static function buildInput(string $request): DeleteUserInput
    {
        $json = json_decode($request, true);
        //проверка, что строки не пустые
        return new DeleteUserInput($json['userId']);
    }
}