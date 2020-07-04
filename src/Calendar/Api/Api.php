<?php


namespace App\Calendar\Api;

use App\Calendar\Api\Input\CreateInvitationInput;
use App\Calendar\Api\Input\CreateMeetingInput;
use App\Calendar\Api\Input\CreateUserInput;
use App\Calendar\Api\Input\DeleteMeetingInput;
use App\Calendar\Api\Input\DeleteUserFromMeetingInput;
use App\Calendar\App\Command\CreateInvitationCommand;
use App\Calendar\App\Command\CreateMeetingCommand;
use App\Calendar\App\Command\CreateUserCommand;
use App\Calendar\App\Command\DeleteMeetingCommand;
use App\Calendar\App\Command\DeleteUserFromMeetingCommand;
use App\Calendar\App\Command\Handler\CreateInvitationCommandHandler;
use App\Calendar\App\Command\Handler\CreateMeetingCommandHandler;
use App\Calendar\App\Command\Handler\CreateUserCommandHandler;
use App\Calendar\App\Command\Handler\DeleteMeetingCommandHandler;
use App\Calendar\App\Command\Handler\DeleteUserFromMeetingCommandHandler;
use App\Calendar\Domain\Exception\MeetingOrganizerIsNotExistException;
use App\Calendar\Domain\Exception\UserAlreadyExistException;
use App\Calendar\Domain\Exception\MeetingParticipantAmountExceedsLimitException;
use App\Calendar\Domain\Exception\UserIsAlreadyMeetingParticipantException;
use App\Calendar\Domain\Exception\UserIsNotMeetingOrganizerException;
use App\Calendar\Domain\Exception\UserIsNotMeetingParticipantException;

class Api implements ApiCommandInterface, ApiQueryInterface
{
    private CreateUserCommandHandler $createUserCommandHandler;
    private CreateMeetingCommandHandler $createMeetingCommandHandler;
    private CreateInvitationCommandHandler $createInvitationCommandHandler;
    private DeleteUserFromMeetingCommandHandler $deleteUserFromMeetingCommandHandler;
    private DeleteMeetingCommandHandler $deleteMeetingCommandHandler;

    public function __construct(
        CreateUserCommandHandler $createUserCommandHandler,
        CreateMeetingCommandHandler $createMeetingCommandHandler,
        CreateInvitationCommandHandler $createInvitationCommandHandler,
        DeleteUserFromMeetingCommandHandler $deleteUserFromMeetingCommandHandler,
        DeleteMeetingCommandHandler $deleteMeetingCommandHandler
    ) {
        $this->createUserCommandHandler = $createUserCommandHandler;
        $this->createMeetingCommandHandler = $createMeetingCommandHandler;
        $this->createInvitationCommandHandler = $createInvitationCommandHandler;
        $this->deleteUserFromMeetingCommandHandler = $deleteUserFromMeetingCommandHandler;
        $this->deleteMeetingCommandHandler = $deleteMeetingCommandHandler;
    }

    public function createUser(CreateUserInput $input): string
    {
        $command = new CreateUserCommand(
            $input->getLogin(),
            $input->getSurname(),
            $input->getName(),
            $input->getPatronymic()
        );

        try
        {
            return $this->createUserCommandHandler->handle($command);
        }
        catch (UserAlreadyExistException $e)
        {
            throw new Exception\UserAlreadyExistException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function createMeeting(CreateMeetingInput $input): string
    {
        $command = new CreateMeetingCommand (
            $input->getOrganizerId(),
            $input->getName(),
            $input->getLocation(),
            $input->getStartTime()
        );

        try
        {
            return $this->createMeetingCommandHandler->handle($command);
        }
        catch (MeetingOrganizerIsNotExistException $e)
        {
            throw new Exception\MeetingOrganizerIsNotExistException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function createInvitation(CreateInvitationInput $input): string
    {
        $command = new CreateInvitationCommand(
            $input->getOrganizerId(),
            $input->getMeetingId(),
            $input->getParticipantId()
        );

        try
        {
            return $this->createInvitationCommandHandler->handle($command);
        }
        catch (MeetingParticipantAmountExceedsLimitException $e)
        {
            throw new Exception\MeetingParticipantAmountExceedsLimitException($e->getMessage(), $e->getCode(), $e);
        }
        catch (UserIsNotMeetingOrganizerException $e)
        {
            throw new Exception\UserIsNotMeetingOrganizerException($e->getMessage(), $e->getCode(), $e);
        }
        catch (UserIsAlreadyMeetingParticipantException $e)
        {
            throw new Exception\UserIsAlreadyMeetingParticipantException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function deleteUserFromMeeting(DeleteUserFromMeetingInput $input): string
    {
        $command = new DeleteUserFromMeetingCommand(
            $input->getOrganizerId(),
            $input->getMeetingId(),
            $input->getParticipantId()
        );

        try
        {
            return $this->deleteUserFromMeetingCommandHandler->handle($command);
        }
        catch (UserIsNotMeetingOrganizerException $e)
        {
            throw new Exception\UserIsNotMeetingOrganizerException($e->getMessage(), $e->getCode(), $e);
        }
        catch (UserIsNotMeetingParticipantException $e)
        {
            throw new Exception\UserIsNotMeetingParticipantException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function deleteMeeting(DeleteMeetingInput $input): string
    {
        $command = new DeleteMeetingCommand(
            $input->getOrganizerId(),
            $input->getMeetingId()
        );

        try
        {
            return $this->deleteMeetingCommandHandler->handle($command);
        }
        catch (UserIsNotMeetingOrganizerException $e)
        {
            throw new Exception\UserIsNotMeetingOrganizerException($e->getMessage(), $e->getCode(), $e);
        }
    }
}