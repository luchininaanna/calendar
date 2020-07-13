<?php


namespace App\Calendar\App\Query;


use App\Calendar\Api\Input\GetParticipantInput;
use App\Calendar\App\Query\Data\MeetingData;
use App\Calendar\App\Query\Data\UserData;

interface UserQueryServiceInterface
{
    /**
     * @return UserData[]
     */
    public function getAllUsers(): array;

    /**
     * @param string $loggedUserId
     * @return MeetingData[]
     */
    public function getMeetingsByParticipant(string $loggedUserId): array;

    /**
     * @param string $loggedUserId
     * @return MeetingData[]
     */
    public function getMeetingsByOrganizer(string $loggedUserId): array;

    /**
     * @param string $loggedUserId
     * @param string $meetingId
     * @return array
     */
    public function getParticipantsAsOrganizer(string $loggedUserId, string $meetingId): array;
}