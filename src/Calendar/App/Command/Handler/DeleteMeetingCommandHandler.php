<?php


namespace App\Calendar\App\Command\Handler;


use App\Calendar\App\Command\DeleteMeetingCommand;
use App\Calendar\App\Uuid\UuidProviderInterface;
use App\Calendar\Domain\Exception\MeetingIsNotExistException;
use App\Calendar\Domain\Exception\UserIsNotMeetingOrganizerException;
use App\Calendar\Domain\Model\Meeting;
use App\Calendar\Domain\Service\MeetingService;
use DateTime;

class DeleteMeetingCommandHandler
{
    private UuidProviderInterface $uuidProvider;
    private MeetingService $meetingService;

    public function __construct(UuidProviderInterface $uuidProvider, MeetingService $meetingService)
    {
        $this->uuidProvider = $uuidProvider;
        $this->meetingService = $meetingService;
    }

    /**
     * @param DeleteMeetingCommand $command
     * @return void
     * @throws MeetingIsNotExistException
     * @throws UserIsNotMeetingOrganizerException
     */
    public function handle(DeleteMeetingCommand $command): void
    {
        $this->meetingService->deleteMeeting($command->getMeetingId(), $command->getLoggedUserId());
    }
}