<?php


namespace App\Calendar\Api;


use App\Calendar\Api\Output\MeetingOutput;
use App\Calendar\Api\Output\UserOutput;
use App\Calendar\App\Query\Data\ParticipantMeetingData;

interface ApiQueryInterface
{
    /**
     * @return UserOutput[]
     */
    public function getAllUsers(): array;


    /**
     * @param string $loggedUserId
     * @return MeetingOutput[]
     */
    public function getMeetingsWithParticipant(string $loggedUserId): array;

    /**
     * @param string $loggedUserId
     * @return MeetingOutput[]
     */
    public function getMeetingsWithOrganizer(string $loggedUserId): array;
}