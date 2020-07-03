<?php


namespace App\Calendar\Api;


use App\Calendar\Api\Exception\UserAlreadyExistException;
use App\Calendar\Api\Input\CreateInvitationInput;
use App\Calendar\Api\Input\CreateMeetingInput;
use App\Calendar\Api\Input\CreateUserInput;

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
     * @return string
     */
    public function createMeeting(CreateMeetingInput $input): string;

    /**
     * @param CreateInvitationInput $input
     * @return string
     */
    public function createInvitation(CreateInvitationInput $input): string;
}