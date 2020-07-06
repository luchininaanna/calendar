<?php


namespace App\Calendar\App\Command\Handler;


use App\Calendar\App\Command\DeleteUserFromMeetingCommand;
use App\Calendar\App\Uuid\UuidProviderInterface;
use App\Calendar\Domain\Exception\UserIsNotMeetingParticipantException;
use App\Calendar\Domain\Exception\UserIsNotMeetingOrganizerException;
use App\Calendar\Domain\Model\MeetingParticipant;
use App\Calendar\Domain\Service\MeetingService;

class DeleteUserFromMeetingCommandHandler
{
    private UuidProviderInterface $uuidProvider;
    private MeetingService $meetingService;

    public function __construct(UuidProviderInterface $uuidProvider, MeetingService $meetingService)
    {
        $this->uuidProvider = $uuidProvider;
        $this->meetingService = $meetingService;
    }

    /**
     * @param DeleteUserFromMeetingCommand $command
     * @return void
     * @throws UserIsNotMeetingOrganizerException
     * @throws UserIsNotMeetingParticipantException
     */
    public function handle(DeleteUserFromMeetingCommand $command): void
    {
        $meetingParticipant = new MeetingParticipant(
            $command->getMeetingId(),
            $command->getParticipantId()
        );

        $this->meetingService->deleteUserFromMeeting($command->getLoggedUserId(), $meetingParticipant);
    }
}