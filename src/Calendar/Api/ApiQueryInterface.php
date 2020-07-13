<?php


namespace App\Calendar\Api;


use App\Calendar\Api\Input\GetParticipantInput;
use App\Calendar\Api\Output\ParticipantOutput;
use App\Calendar\Api\Output\MeetingOutput;
use App\Calendar\Api\Output\UserOutput;

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

    /**
     * @param GetParticipantInput $getParticipantInput
     * @return ParticipantOutput[]
     */
    public function getParticipantsWithOrganizer(GetParticipantInput $getParticipantInput): array;
}