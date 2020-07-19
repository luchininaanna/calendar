<?php


namespace App\Calendar\App\Query\Data;

class UserData
{
    private string $id;
    private string $name;
    private string $login;
    private string $surname;
    private string $patronymic;

    public function __construct(string $id, string $login, string $name, string $surname, string $patronymic)
    {
        $this->id = $id;
        $this->name = $name;
        $this->login = $login;
        $this->surname = $surname;
        $this->patronymic = $patronymic;
    }

    public function getId(): string
    {
        return $this->id;
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