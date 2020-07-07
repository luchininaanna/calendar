<?php


namespace App\Calendar\App\Query\Data;


class ParticipantData
{
    private string $uuid;
    private string $login;
    private string $name;
    private string $surname;
    private string $patronymic;
    private string $start_time;

    public function __construct(string $uuid, string $login, string $name, string $surname, string $patronymic, string $start_time)
    {
        $this->uuid = $uuid;
        $this->login = $login;
        $this->name = $name;
        $this->surname = $surname;
        $this->patronymic = $patronymic;
        $this->start_time = $start_time;
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

    public function getStartTime(): string
    {
        return $this->start_time;
    }
}