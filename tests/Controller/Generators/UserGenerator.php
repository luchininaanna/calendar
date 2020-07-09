<?php


namespace App\Tests\Controller\Generators;


class UserGenerator
{
    public function createUserModel(): array
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $login = substr(str_shuffle($permitted_chars), 0, 10);
        return [
            "login" => $login,
            "name" => "name",
            "surname" => "surname",
            "patronymic" => "partonymic",
        ];
    }

    public function createUserWithEmptyFieldsModel(): array
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $login = substr(str_shuffle($permitted_chars), 0, 10);
        return [
            "userId" => $login,
            "name" => "",
            "surname" => "",
            "patronymic" => "",
        ];
    }
}