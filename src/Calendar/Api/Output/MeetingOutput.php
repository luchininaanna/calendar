<?php


namespace App\Calendar\Api\Output;


use App\Calendar\App\Query\Data\MeetingData;

class MeetingOutput
{
    private MeetingData $meetingData;

    public function __construct(MeetingData $meetingData)
    {
        $this->meetingData = $meetingData;
    }

    public function asAssoc(): array
    {
        return [
            'uuid' => $this->meetingData->getUuid(),
            'name' => $this->meetingData->getName(),
            'location' => $this->meetingData->getLocation(),
            'start_time' => $this->meetingData->getStartTime(),
        ];
    }
}