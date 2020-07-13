<?php


namespace App\Calendar\Api\Output;


use App\Calendar\App\Query\Data\MeetingData;

class MeetingOutput
{
    private MeetingData $participantMeetingData;

    public function __construct(MeetingData $participantMeetingData)
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