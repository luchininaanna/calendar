<?php


namespace App\Calendar\Api\Output;


use App\Calendar\App\Query\Data\ParticipantMeetingData;

class MeetingOutput
{
    private ParticipantMeetingData $participantMeetingData;

    public function __construct(ParticipantMeetingData $participantMeetingData)
    {
        $this->participantMeetingData = $participantMeetingData;
    }

    public function asAssoc(): array
    {
        return [
            'uuid' => $this->participantMeetingData->getUuid(),
            'name' => $this->participantMeetingData->getName(),
            'location' => $this->participantMeetingData->getLocation(),
            'start_time' => $this->participantMeetingData->getStartTime(),
        ];
    }
}