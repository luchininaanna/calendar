<?php


namespace App\Calendar\App\Command\Handler;


use App\Calendar\App\Command\InviteMeetingParticipantCommand;
use App\Calendar\App\Synchronization\SynchronizationInterface;
use App\Calendar\App\Uuid\UuidProviderInterface;
use App\Calendar\Domain\Exception\MeetingParticipantAmountExceedsLimitException;
use App\Calendar\Domain\Exception\UserIsAlreadyMeetingParticipantException;
use App\Calendar\Domain\Exception\UserIsNotMeetingOrganizerException;
use App\Calendar\Domain\Model\MeetingParticipant;
use App\Calendar\Domain\Service\MeetingService;

class InviteMeetingParticipantCommandHandler
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
     * @param InviteMeetingParticipantCommand $command
     * @return string
     * @throws MeetingParticipantAmountExceedsLimitException
     * @throws UserIsNotMeetingOrganizerException
     * @throws UserIsAlreadyMeetingParticipantException
     */
    public function handle(InviteMeetingParticipantCommand $command): void
    {
        $this->synchronization->transaction(function() use ($command) {
            $meetingParticipant = new MeetingParticipant(
                $command->getMeetingId(),
                $command->getParticipantId()
            );

            $this->meetingService->createMeetingParticipant($command->getLoggedUserId(), $meetingParticipant);
        });
    }
}