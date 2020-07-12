<?php


namespace App\Calendar\App\Command\Handler;


use App\Calendar\App\Command\DeleteUserCommand;
use App\Calendar\App\Synchronization\SynchronizationInterface;
use App\Calendar\App\Uuid\UuidProviderInterface;
use App\Calendar\Domain\Exception\UserIsNotExistException;
use App\Calendar\Domain\Service\MeetingService;
use App\Calendar\Domain\Service\UserService;
use Symfony\Component\HttpKernel\Log\Logger;

class DeleteUserCommandHandler
{
    private UuidProviderInterface $uuidProvider;
    private UserService $userService;
    private MeetingService $meetingService;
    private SynchronizationInterface $synchronization;

    public function __construct(
        UuidProviderInterface $uuidProvider,
        MeetingService $meetingService,
        UserService $userService,
        SynchronizationInterface $synchronization
    ) {
        $this->uuidProvider = $uuidProvider;
        $this->meetingService = $meetingService;
        $this->userService = $userService;
        $this->synchronization = $synchronization;
    }

    /**
     * @param DeleteUserCommand $command
     * @return void
     * @throws UserIsNotExistException
     */
    public function handle(DeleteUserCommand $command): void
    {
        $this->synchronization->transaction(function() use ($command) {
            $this->meetingService->deleteUserFromMeetings($command->getUserId());
            $this->userService->deleteUser($command->getUserId());
        });
    }
}