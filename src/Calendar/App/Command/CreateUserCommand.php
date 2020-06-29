<?php


namespace App\Calendar\App\Command;


class CreateUserCommand
{
    private string $login;
    private string $surname;
    private string $name;
    private string $patronymic;

    public function __construct(string $login, string $surname, string $name, string $patronymic)
    {
        $this->login = $login;
        $this->surname = $surname;
        $this->name = $name;
        $this->patronymic = $patronymic;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPatronymic(): string
    {
        return $this->patronymic;
    }
}