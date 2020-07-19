<?php


namespace App\Calendar\App\Command\Handler;


use App\Calendar\App\Command\CreateUserCommand;
use App\Calendar\App\Synchronization\SynchronizationInterface;
use App\Calendar\App\Uuid\UuidProviderInterface;
use App\Calendar\Domain\Exception\UserAlreadyExistException;
use App\Calendar\Domain\Model\User;
use App\Calendar\Domain\Service\UserService;

class CreateUserCommandHandler
{
    private UserService $userService;
    private UuidProviderInterface $uuidProvider;
    private SynchronizationInterface $synchronization;

    public function __construct(
        UuidProviderInterface $uuidProvider,
        UserService $userService,
        SynchronizationInterface $synchronization
    ) {
        $this->userService = $userService;
        $this->uuidProvider = $uuidProvider;
        $this->synchronization = $synchronization;
    }

    /**
     * @param CreateUserCommand $command
     * @return string
     * @throws UserAlreadyExistException
     */
    public function handle(CreateUserCommand $command): string
    {
        return $this->synchronization->transaction(function() use ($command) {
            $user = new User(
                $this->uuidProvider->generate(),
                $command->getLogin(),
                $command->getSurname(),
                $command->getName(),
                $command->getPatronymic()
            );

            $this->userService->createUser($user);

            return $user->getId();
        });
    }
}