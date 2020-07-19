<?php


namespace App\Calendar\Api\Output;


use App\Calendar\App\Query\Data\ParticipantData;

class ParticipantOutput
{
    private ParticipantData $participantData;

    public function __construct(ParticipantData $participantData)
    {
        $this->participantData = $participantData;
    }

    public function asAssoc(): array
    {
        return [
            'uuid' => $this->participantData->getId(),
            'login' => $this->participantData->getLogin(),
            'name' => $this->participantData->getName(),
            'surname' => $this->participantData->getSurname(),
            'patronymic' => $this->participantData->getPatronymic(),
            'start_time' => $this->participantData->getStartTime(),
        ];
    }
}