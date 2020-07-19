<?php


namespace App\Calendar\App\Command\Handler;


use App\Calendar\App\Command\DeleteMeetingParticipantCommand;
use App\Calendar\App\Synchronization\SynchronizationInterface;
use App\Calendar\App\Uuid\UuidProviderInterface;
use App\Calendar\Domain\Exception\MeetingParticipantIsNotCorrectException;
use App\Calendar\Domain\Exception\MeetingOrganizerIsNotCorrectException;
use App\Calendar\Domain\Model\MeetingParticipant;
use App\Calendar\Domain\Service\MeetingService;

class DeleteMeetingParticipantCommandHandler
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
     * @param DeleteMeetingParticipantCommand $command
     * @return void
     * @throws MeetingOrganizerIsNotCorrectException
     * @throws MeetingParticipantIsNotCorrectException
     */
    public function handle(DeleteMeetingParticipantCommand $command): void
    {
        $this->synchronization->transaction(function() use ($command) {
            $this->meetingService->deleteMeetingParticipant(
                $command->getInvokerId(),
                $command->getMeetingId(),
                $command->getParticipantId()
            );
        });
    }
}