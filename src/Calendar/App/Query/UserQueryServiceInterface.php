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
     * @param string $invokerId
     * @return MeetingData[]
     */
    public function getMeetingsByParticipant(string $invokerId): array;

    /**
     * @param string $invokerId
     * @return MeetingData[]
     */
    public function getMeetingsByOrganizer(string $invokerId): array;

    /**
     * @param string $invokerId
     * @param string $meetingId
     * @return array
     */
    public function getParticipantsAsOrganizer(string $invokerId, string $meetingId): array;
}