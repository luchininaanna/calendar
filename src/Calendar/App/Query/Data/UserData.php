<?php


namespace App\Calendar\App\Query\Data;

class UserData
{
    private string $uuid;
    private string $login;
    private string $name;
    private string $surname;
    private string $patronymic;

    public function __construct(string $uuid, string $login, string $name, string $surname, string $patronymic)
    {
        $this->uuid = $uuid;
        $this->login = $login;
        $this->name = $name;
        $this->surname = $surname;
        $this->patronymic = $patronymic;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getPatronymic(): string
    {
        return $this->patronymic;
    }
}