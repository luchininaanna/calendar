<?php


namespace App\Calendar\App\Command\Handler;


use App\Calendar\App\Command\DeleteUserCommand;
use App\Calendar\App\Synchronization\SynchronizationInterface;
use App\Calendar\Domain\Exception\UserIsNotExistException;
use App\Calendar\Domain\Service\MeetingService;
use App\Calendar\Domain\Service\UserService;

class DeleteUserCommandHandler
{
    private UserService $userService;
    private MeetingService $meetingService;
    private SynchronizationInterface $synchronization;

    public function __construct(
        MeetingService $meetingService,
        UserService $userService,
        SynchronizationInterface $synchronization
    ) {
        $this->userService = $userService;
        $this->meetingService = $meetingService;
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
            $this->meetingService->deleteParticipantFromAllMeetings($command->getUserId());
            $this->meetingService->deleteAllMeetingsForOrganizer($command->getUserId());
            $this->userService->deleteUser($command->getUserId());
        });
    }
}