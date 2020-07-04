<?php


namespace App\Calendar\Api;


use App\Calendar\Api\Input\CreateInvitationInput;
use App\Calendar\Api\Input\CreateMeetingInput;
use App\Calendar\Api\Input\CreateUserInput;
use App\Calendar\Api\Input\DeleteUserFromMeetingInput;
use App\Calendar\App\Command\CreateInvitationCommand;
use App\Calendar\App\Command\CreateMeetingCommand;
use App\Calendar\App\Command\CreateUserCommand;
use App\Calendar\App\Command\DeleteUserFromMeetingCommand;
use App\Calendar\App\Command\Handler\CreateInvitationCommandHandler;
use App\Calendar\App\Command\Handler\CreateMeetingCommandHandler;
use App\Calendar\App\Command\Handler\CreateUserCommandHandler;
use App\Calendar\App\Command\Handler\DeleteUserFromMeetingCommandHandler;
use App\Calendar\Domain\Exception\UserAlreadyExistException;

class Api implements ApiCommandInterface, ApiQueryInterface
{
    private CreateUserCommandHandler $createUserCommandHandler;
    private CreateMeetingCommandHandler $createMeetingCommandHandler;
    private CreateInvitationCommandHandler $createInvitationCommandHandler;
    private DeleteUserFromMeetingCommandHandler $deleteUserFromMeetingCommandHandler;

    public function __construct(
        CreateUserCommandHandler $createUserCommandHandler,
        CreateMeetingCommandHandler $createMeetingCommandHandler,
        CreateInvitationCommandHandler $createInvitationCommandHandler,
        DeleteUserFromMeetingCommandHandler $deleteUserFromMeetingCommandHandler
    ) {
        $this->createUserCommandHandler = $createUserCommandHandler;
        $this->createMeetingCommandHandler = $createMeetingCommandHandler;
        $this->createInvitationCommandHandler = $createInvitationCommandHandler;
        $this->deleteUserFromMeetingCommandHandler = $deleteUserFromMeetingCommandHandler;
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

        return $this->createMeetingCommandHandler->handle($command);
    }

    public function createInvitation(CreateInvitationInput $input): string
    {
        $command = new CreateInvitationCommand(
            $input->getOrganizerId(),
            $input->getMeetingId(),
            $input->getParticipantId()
        );

        return $this->createInvitationCommandHandler->handle($command);
    }

    public function deleteUserFromMeeting(DeleteUserFromMeetingInput $input): string
    {
        $command = new DeleteUserFromMeetingCommand(
            $input->getOrganizerId(),
            $input->getMeetingId(),
            $input->getParticipantId()
        );

        return $this->deleteUserFromMeetingCommandHandler->handle($command);
    }
}