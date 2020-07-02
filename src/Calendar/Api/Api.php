<?php


namespace App\Calendar\Api;


use App\Calendar\Api\Input\CreateMeetingInput;
use App\Calendar\Api\Input\CreateUserInput;
use App\Calendar\App\Command\CreateMeetingCommand;
use App\Calendar\App\Command\CreateUserCommand;
use App\Calendar\App\Command\Handler\CreateMeetingCommandHandler;
use App\Calendar\App\Command\Handler\CreateUserCommandHandler;
use App\Calendar\Domain\Exception\UserAlreadyExistException;

class Api implements ApiCommandInterface, ApiQueryInterface
{
    private CreateUserCommandHandler $createUserCommandHandler;
    private CreateMeetingCommandHandler $createMeetingCommandHandler;

    public function __construct(
        CreateUserCommandHandler $createUserCommandHandler,
        CreateMeetingCommandHandler $createMeetingCommandHandler
    ) {
        $this->createUserCommandHandler = $createUserCommandHandler;
        $this->createMeetingCommandHandler = $createMeetingCommandHandler;
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
}