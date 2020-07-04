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
     * @return string
     * @throws MeetingIsNotExistException
     * @throws UserIsNotMeetingOrganizerException
     */
    public function handle(DeleteMeetingCommand $command): string
    {
        $meeting = new Meeting(
            $command->getMeetingId(),
            $command->getOrganizerId(),
            "",
            "",
            new DateTime()
        );

        $this->meetingService->deleteMeeting($meeting);

        return $meeting->getUuid();
    }
}