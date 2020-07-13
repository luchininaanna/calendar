<?php


namespace App\Calendar\Api;


use App\Calendar\Api\Exception\MeetingIsNotExistException;
use App\Calendar\Api\Exception\UserAlreadyExistException;
use App\Calendar\Api\Exception\UserIsNotExistException;
use App\Calendar\Api\Exception\UserIsNotMeetingParticipantException;
use App\Calendar\Api\Input\CreateMeetingParticipantInput;
use App\Calendar\Api\Input\CreateMeetingInput;
use App\Calendar\Api\Input\CreateUserInput;
use App\Calendar\Api\Input\DeleteMeetingInput;
use App\Calendar\Api\Input\DeleteMeetingParticipantInput;
use App\Calendar\Api\Exception\MeetingOrganizerIsNotExistException;
use App\Calendar\Api\Exception\MeetingParticipantAmountExceedsLimitException;
use App\Calendar\Api\Exception\UserIsAlreadyMeetingParticipantException;
use App\Calendar\Api\Exception\UserIsNotMeetingOrganizerException;
use App\Calendar\Api\Input\DeleteUserInput;

interface ApiCommandInterface
{
    /**
     * @param CreateUserInput $input
     * @throws UserAlreadyExistException
     * @return string
     */
    public function createUser(CreateUserInput $input): string;

    /**
     * @param CreateMeetingInput $input
     * @throws MeetingOrganizerIsNotExistException
     * @return string
     */
    public function createMeeting(CreateMeetingInput $input): string;

    /**
     * @param CreateMeetingParticipantInput $input
     * @return void
     * @throws UserIsNotExistException
     * @throws UserIsNotMeetingOrganizerException
     * @throws UserIsAlreadyMeetingParticipantException
     * @throws MeetingParticipantAmountExceedsLimitException
     */
    public function inviteMeetingParticipant(CreateMeetingParticipantInput $input): void;

    /**
     * @param DeleteMeetingParticipantInput $input
     * @return void
     * @throws UserIsNotMeetingOrganizerException
     * @throws UserIsNotMeetingParticipantException
     */
    public function deleteMeetingParticipant(DeleteMeetingParticipantInput $input): void;

    /**
     * @param DeleteMeetingInput $input
     * @return void
     * @throws MeetingIsNotExistException
     * @throws UserIsNotMeetingOrganizerException
     */
    public function deleteMeeting(DeleteMeetingInput $input): void;

    /**
     * @param DeleteUserInput $input
     * @return void
     * @throws UserIsNotExistException
     */
    public function deleteUser(DeleteUserInput $input): void;
}