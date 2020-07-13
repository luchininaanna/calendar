<?php


namespace App\Calendar\Api\Output;


use App\Calendar\App\Query\Data\ParticipantData;

class ParticipantOutput
{
    private ParticipantData $invitationData;

    public function __construct(ParticipantData $invitationData)
    {
        $this->invitationData = $invitationData;
    }

    private string $uuid;
    private string $login;
    private string $name;
    private string $surname;
    private string $patronymic;
    private string $start_time;

    public function asAssoc(): array
    {
        return [
            'uuid' => $this->invitationData->getUuid(),
            'login' => $this->invitationData->getLogin(),
            'name' => $this->invitationData->getName(),
            'surname' => $this->invitationData->getSurname(),
            'patronymic' => $this->invitationData->getPatronymic(),
            'start_time' => $this->invitationData->getStartTime(),
        ];
    }
}