<?php


namespace App\Calendar\Api;

use App\Calendar\Api\Input\CreateInvitationInput;
use App\Calendar\Api\Input\CreateMeetingInput;
use App\Calendar\Api\Input\CreateUserInput;
use App\Calendar\Api\Input\DeleteMeetingInput;
use App\Calendar\Api\Input\DeleteUserFromMeetingInput;
use App\Calendar\Api\Input\DeleteUserInput;
use App\Calendar\App\Command\CreateInvitationCommand;
use App\Calendar\App\Command\CreateMeetingCommand;
use App\Calendar\App\Command\CreateUserCommand;
use App\Calendar\App\Command\DeleteMeetingCommand;
use App\Calendar\App\Command\DeleteUserCommand;
use App\Calendar\App\Command\DeleteUserFromMeetingCommand;
use App\Calendar\App\Command\Handler\CreateInvitationCommandHandler;
use App\Calendar\App\Command\Handler\CreateMeetingCommandHandler;
use App\Calendar\App\Command\Handler\CreateUserCommandHandler;
use App\Calendar\App\Command\Handler\DeleteMeetingCommandHandler;
use App\Calendar\App\Command\Handler\DeleteUserCommandHandler;
use App\Calendar\App\Command\Handler\DeleteUserFromMeetingCommandHandler;
use App\Calendar\Domain\Exception\MeetingIsNotExistException;
use App\Calendar\Domain\Exception\MeetingOrganizerIsNotExistException;
use App\Calendar\Domain\Exception\UserAlreadyExistException;
use App\Calendar\Domain\Exception\MeetingParticipantAmountExceedsLimitException;
use App\Calendar\Domain\Exception\UserIsAlreadyMeetingParticipantException;
use App\Calendar\Domain\Exception\UserIsNotExistException;
use App\Calendar\Domain\Exception\UserIsNotMeetingOrganizerException;
use App\Calendar\Domain\Exception\UserIsNotMeetingParticipantException;

class Api implements ApiCommandInterface, ApiQueryInterface
{
    private CreateUserCommandHandler $createUserCommandHandler;
    private CreateMeetingCommandHandler $createMeetingCommandHandler;
    private CreateInvitationCommandHandler $createInvitationCommandHandler;
    private DeleteUserFromMeetingCommandHandler $deleteUserFromMeetingCommandHandler;
    private DeleteMeetingCommandHandler $deleteMeetingCommandHandler;
    private DeleteUserCommandHandler $deleteUserCommandHandler;

    public function __construct(
        CreateUserCommandHandler $createUserCommandHandler,
        CreateMeetingCommandHandler $createMeetingCommandHandler,
        CreateInvitationCommandHandler $createInvitationCommandHandler,
        DeleteUserFromMeetingCommandHandler $deleteUserFromMeetingCommandHandler,
        DeleteMeetingCommandHandler $deleteMeetingCommandHandler,
        DeleteUserCommandHandler $deleteUserCommandHandler
    ) {
        $this->createUserCommandHandler = $createUserCommandHandler;
        $this->createMeetingCommandHandler = $createMeetingCommandHandler;
        $this->createInvitationCommandHandler = $createInvitationCommandHandler;
        $this->deleteUserFromMeetingCommandHandler = $deleteUserFromMeetingCommandHandler;
        $this->deleteMeetingCommandHandler = $deleteMeetingCommandHandler;
        $this->deleteUserCommandHandler = $deleteUserCommandHandler;
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
        $command = new CreateMeetingCommand(
            $input->getLoggedUserId(),
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

    public function deleteUserFromMeeting(DeleteUserFromMeetingInput $input): void
    {
        $command = new DeleteUserFromMeetingCommand(
            $input->getOrganizerId(),
            $input->getMeetingId(),
            $input->getParticipantId()
        );

        try
        {
            $this->deleteUserFromMeetingCommandHandler->handle($command);
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

    public function deleteMeeting(DeleteMeetingInput $input): void
    {
        $command = new DeleteMeetingCommand(
            $input->getOrganizerId(),
            $input->getMeetingId()
        );

        try
        {
            $this->deleteMeetingCommandHandler->handle($command);
        }
        catch (UserIsNotMeetingOrganizerException $e)
        {
            throw new Exception\UserIsNotMeetingOrganizerException($e->getMessage(), $e->getCode(), $e);
        }
        catch ( MeetingIsNotExistException$e)
        {
            throw new Exception\MeetingIsNotExistException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function deleteUser(DeleteUserInput $input): void
    {
        $command = new DeleteUserCommand(
            $input->getUserId()
        );

        try
        {
            $this->deleteUserCommandHandler->handle($command);
        }
        catch (UserIsNotExistException $e)
        {
            throw new Exception\UserIsNotExistException($e->getMessage(), $e->getCode(), $e);
        }
    }
}