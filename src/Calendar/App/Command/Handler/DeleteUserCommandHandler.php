<?php


namespace App\Calendar\App\Command\Handler;


use App\Calendar\App\Command\DeleteUserCommand;
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

    public function __construct(UuidProviderInterface $uuidProvider, MeetingService $meetingService,
                                UserService $userService)
    {
        $this->uuidProvider = $uuidProvider;
        $this->meetingService = $meetingService;
        $this->userService = $userService;
    }

    /**
     * @param DeleteUserCommand $command
     * @return string
     * @throws UserIsNotExistException
     */
    public function handle(DeleteUserCommand $command): string
    {
        $logger = new Logger();
        $logger->info('I just got the logger--------------------');

        //транзакция
        $this->meetingService->deleteUserFromMeetings($command->getUserId());
        $this->userService->deleteUser($command->getUserId());

        return $command->getUserId();
    }
}