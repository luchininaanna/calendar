<?php


namespace App\Calendar\App\Command\Handler;


use App\Calendar\App\Command\DeleteUserFromMeetingCommand;
use App\Calendar\App\Synchronization\SynchronizationInterface;
use App\Calendar\App\Uuid\UuidProviderInterface;
use App\Calendar\Domain\Exception\UserIsNotMeetingParticipantException;
use App\Calendar\Domain\Exception\UserIsNotMeetingOrganizerException;
use App\Calendar\Domain\Model\MeetingParticipant;
use App\Calendar\Domain\Service\MeetingService;

class DeleteUserFromMeetingCommandHandler
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
     * @param DeleteUserFromMeetingCommand $command
     * @return void
     * @throws UserIsNotMeetingOrganizerException
     * @throws UserIsNotMeetingParticipantException
     */
    public function handle(DeleteUserFromMeetingCommand $command): void
    {
        $this->synchronization->transaction(function() use ($command) {
            $meetingParticipant = new MeetingParticipant(
                $command->getMeetingId(),
                $command->getParticipantId()
            );

            $this->meetingService->deleteUserFromMeeting($command->getLoggedUserId(), $meetingParticipant);
        });
    }
}