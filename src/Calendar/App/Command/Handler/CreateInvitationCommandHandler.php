<?php


namespace App\Calendar\App\Command\Handler;


use App\Calendar\App\Command\CreateInvitationCommand;
use App\Calendar\App\Uuid\UuidProviderInterface;
use App\Calendar\Domain\Exception\MeetingParticipantAmountExceedsLimitException;
use App\Calendar\Domain\Exception\UserIsAlreadyMeetingParticipantException;
use App\Calendar\Domain\Exception\UserIsNotMeetingOrganizerException;
use App\Calendar\Domain\Model\MeetingParticipant;
use App\Calendar\Domain\Service\MeetingService;

class CreateInvitationCommandHandler
{
    private UuidProviderInterface $uuidProvider;
    private MeetingService $meetingService;

    public function __construct(UuidProviderInterface $uuidProvider, MeetingService $meetingService)
    {
        $this->uuidProvider = $uuidProvider;
        $this->meetingService = $meetingService;
    }

    /**
     * @param CreateInvitationCommand $command
     * @return string
     * @throws MeetingParticipantAmountExceedsLimitException
     * @throws UserIsNotMeetingOrganizerException
     * @throws UserIsAlreadyMeetingParticipantException
     */
    public function handle(CreateInvitationCommand $command): string
    {
        $meetingParticipant = new MeetingParticipant(
            $this->uuidProvider->generate(),
            $command->getOrganizerId(),
            $command->getMeetingId(),
            $command->getParticipantId()
        );

        $this->meetingService->createMeetingParticipant($meetingParticipant);

        return $meetingParticipant->getUuid();
    }
}