<?php


namespace App\Calendar\Api\Output;


use App\Calendar\App\Query\Data\UserData;

class UserOutput
{
    private UserData $userData;

    public function __construct(UserData $userData)
    {
        $this->userData = $userData;
    }

    public function asAssoc(): array
    {
        return [
            'uuid' => $this->userData->getId(),
            'login' => $this->userData->getLogin(),
            'name' => $this->userData->getName(),
            'surname' => $this->userData->getSurname(),
            'patronymic' => $this->userData->getPatronymic(),
        ];
    }
}