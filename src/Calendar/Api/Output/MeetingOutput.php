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
            'uuid' => $this->participantMeetingData->getUuid(),
            'name' => $this->participantMeetingData->getName(),
            'location' => $this->participantMeetingData->getLocation(),
            'start_time' => $this->participantMeetingData->getStartTime(),
        ];
    }
}