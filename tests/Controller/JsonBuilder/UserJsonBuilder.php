<?php


namespace App\Tests\Controller\JsonBuilder;


class UserJsonBuilder
{
    private const PERMITTED_CHARS = '0123456789abcdefghijklmnopqrstuvwxyz';

    public function createUserJson(): array
    {
        return [
            "login" => $this->getRandomLogin(),
            "name" => "name",
            "surname" => "surname",
            "patronymic" => "partonymic",
        ];
    }

    public function createUserWithEmptyFieldsJson(): array
    {
        return [
            "userId" => $this->getRandomLogin(),
            "name" => "",
            "surname" => "",
            "patronymic" => "",
        ];
    }

    private function getRandomLogin(): string
    {
        return substr(str_shuffle(self::PERMITTED_CHARS), 0, 10);
    }
}