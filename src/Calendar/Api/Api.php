<?php


namespace App\Calendar\Api;


use App\Calendar\Api\Input\CreateUserInput;
use App\Calendar\App\Command\CreateUserCommand;
use App\Calendar\App\Command\Handler\CreateUserCommandHandler;

class Api implements ApiCommandInterface, ApiQueryInterface
{
    private CreateUserCommandHandler $createUserCommandHandler;

    public function __construct(
        CreateUserCommandHandler $createUserCommandHandler
    ) {
        $this->createUserCommandHandler = $createUserCommandHandler;
    }

    public function createUser(CreateUserInput $input): void
    {
        $command = new CreateUserCommand(
            $input->getLogin(),
            $input->getSurname(),
            $input->getName(),
            $input->getPatronymic()
        );

        $this->createUserCommandHandler->handle($command);
    }
}