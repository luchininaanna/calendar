<?php


namespace App\Controller\Mapper;


use App\Calendar\Api\Input\CreateUserInput;

class CreateUserRequestMapper
{
    public static function buildInput(string $request): ?CreateUserInput
    {
        $json = json_decode($request, true);
        if (empty($json['login']) || empty($json['surname']) || empty($json['name']) || empty($json['patronymic']))
        {
            return null;
        }

        return new CreateUserInput($json['login'], $json['surname'], $json['name'], $json['patronymic']);
    }
}