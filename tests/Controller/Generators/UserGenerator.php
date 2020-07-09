<?php


namespace App\Tests\Controller\Generators;


class UserGenerator
{
    public function createRandomJsonUser(): array
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $login = substr(str_shuffle($permitted_chars), 0, 10);
        $name = substr(str_shuffle($permitted_chars), 0, 5);
        $randomUser =
            [
                "login" => $login,
                "name" => $name,
                "surname" => "surname",
                "patronymic" => "partonymic",
            ];

        return $randomUser;
    }

    public function createJsonUserWithEmptyFields(): array
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $login = substr(str_shuffle($permitted_chars), 0, 10);
        $randomUser =
            [
                "login" => $login,
                "name" => "",
                "surname" => "",
                "patronymic" => "",
            ];

        return $randomUser;
    }
}