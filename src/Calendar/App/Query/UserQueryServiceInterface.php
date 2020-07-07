<?php


namespace App\Calendar\App\Query;


use App\Calendar\App\Query\Data\ParticipantMeetingData;
use App\Calendar\App\Query\Data\UserData;

interface UserQueryServiceInterface
{
    /**
     * @return UserData[]
     */
    public function getAllUsers(): array;

    /**
     * @param string $loggedUserId
     * @return ParticipantMeetingData[]
     */
    public function getMeetingsWithParticipant(string $loggedUserId): array;

    /**
     * @param string $loggedUserId
     * @return ParticipantMeetingData[]
     */
    public function getMeetingsWithOrganizer(string $loggedUserId): array;
}