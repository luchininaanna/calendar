<?php


namespace App\Calendar\App\Query\Data;


class ParticipantData
{
    private string $uuid;
    private string $name;
    private string $login;
    private string $surname;
    private string $patronymic;
    private string $startTime;

    public function __construct(
        string $uuid,
        string $login,
        string $name,
        string $surname,
        string $patronymic,
        string $startTime
    ) {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->login = $login;
        $this->surname = $surname;
        $this->patronymic = $patronymic;
        $this->startTime = $startTime;
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
        return $this->startTime;
    }
}