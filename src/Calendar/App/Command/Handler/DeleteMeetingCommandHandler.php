<?php


namespace App\Calendar\App\Command\Handler;


use App\Calendar\App\Command\DeleteMeetingCommand;
use App\Calendar\App\Synchronization\SynchronizationInterface;
use App\Calendar\Domain\Exception\MeetingIsNotExistException;
use App\Calendar\Domain\Exception\MeetingOrganizerIsNotCorrectException;
use App\Calendar\Domain\Service\MeetingService;

class DeleteMeetingCommandHandler
{
    private MeetingService $meetingService;
    private SynchronizationInterface $synchronization;

    public function __construct(
        MeetingService $meetingService,
        SynchronizationInterface $synchronization
    ) {
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