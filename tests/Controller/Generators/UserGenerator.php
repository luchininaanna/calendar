<?php


namespace App\Tests\Controller\Generators;


class UserGenerator
{
    public function createUser(): array
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

    public function createUserWithEmptyFields(): array
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $login = substr(str_shuffle($permitted_chars), 0, 10);
        return [
            "login" => $login,
            "name" => "",
            "surname" => "",
            "patronymic" => "",
        ];
    }
}