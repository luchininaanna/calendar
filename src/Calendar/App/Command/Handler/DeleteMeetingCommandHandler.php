<?php


namespace App\Calendar\App\Command\Handler;


use App\Calendar\App\Command\DeleteMeetingCommand;
use App\Calendar\App\Synchronization\SynchronizationInterface;
use App\Calendar\App\Uuid\UuidProviderInterface;
use App\Calendar\Domain\Exception\MeetingIsNotExistException;
use App\Calendar\Domain\Exception\MeetingOrganizerIsNotCorrectException;
use App\Calendar\Domain\Service\MeetingService;

class DeleteMeetingCommandHandler
{
    private MeetingService $meetingService;
    private UuidProviderInterface $uuidProvider;
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
     * @throws MeetingOrganizerIsNotCorrectException
     */
    public function handle(DeleteMeetingCommand $command): void
    {
        $this->synchronization->transaction(function() use ($command) {
            $this->meetingService->deleteMeeting($command->getMeetingId(), $command->getInvokerId());
        });
    }
}