<?php


namespace App\Controller\Mapper;


use App\Calendar\Api\Input\CreateUserInput;

class CreateUserRequestMapper
{
    public static function buildInput(string $request): CreateUserInput
    {
        $json = json_decode($request, true);
        //проверка, что строки не пустые
        return new CreateUserInput($json['login'], $json['surname'], $json['name'], $json['patronymic']);
    }
}