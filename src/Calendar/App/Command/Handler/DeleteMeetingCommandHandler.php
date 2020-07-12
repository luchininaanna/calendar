<?php


namespace App\Calendar\App\Command\Handler;


use App\Calendar\App\Command\DeleteMeetingCommand;
use App\Calendar\App\Synchronization\SynchronizationInterface;
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
    private SynchronizationInterface $synchronization;

    public function __construct(
        UuidProviderInterface $uuidProvider,
        MeetingService $meetingService,
        SynchronizationInterface $synchronization
    ) {
        $this->uuidProvider = $uuidProvider;
        $this->meetingService = $meetingService;
        $this->synchronization = $synchronization;
    }

    /**
     * @param DeleteMeetingCommand $command
     * @return void
     * @throws MeetingIsNotExistException
     * @throws UserIsNotMeetingOrganizerException
     */
    public function handle(DeleteMeetingCommand $command): void
    {
        $this->synchronization->transaction(function() use ($command) {
            $this->meetingService->deleteMeeting($command->getMeetingId(), $command->getLoggedUserId());
        });
    }
}