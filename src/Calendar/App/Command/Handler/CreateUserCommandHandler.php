<?php


namespace App\Calendar\App\Command\Handler;


use App\Calendar\App\Command\CreateUserCommand;
use App\Calendar\App\Uuid\UuidProviderInterface;
use App\Calendar\Domain\Model\User;
use App\Calendar\Domain\Service\UserService;

class CreateUserCommandHandler
{

    private UuidProviderInterface $uuidProvider;
    private UserService $userService;

    public function __construct(UuidProviderInterface $uuidProvider, UserService $userService)
    {
        $this->uuidProvider = $uuidProvider;
        $this->userService = $userService;
    }

    public function handle(CreateUserCommand $command): void
    {
        $user = new User(
            $this->uuidProvider->generate(),
            $command->getLogin(),
            $command->getSurname(),
            $command->getName(),
            $command->getPatronymic()
        );

        $this->userService->createUser($user);
    }
}