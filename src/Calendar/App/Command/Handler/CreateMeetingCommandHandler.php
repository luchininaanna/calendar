<?php


namespace App\Calendar\App\Command\Handler;


use App\Calendar\App\Command\CreateMeetingCommand;
use App\Calendar\App\Uuid\UuidProviderInterface;
use App\Calendar\Domain\Exception\MeetingOrganizerIsNotExistException;
use App\Calendar\Domain\Model\Meeting;
use App\Calendar\Domain\Service\MeetingService;

class CreateMeetingCommandHandler
{
    private UuidProviderInterface $uuidProvider;
    private MeetingService $meetingService;

    public function __construct(UuidProviderInterface $uuidProvider, MeetingService $meetingService)
    {
        $this->uuidProvider = $uuidProvider;
        $this->meetingService = $meetingService;
    }

    /**
     * @param CreateMeetingCommand $command
     * @return string
     * @throws MeetingOrganizerIsNotExistException
     */
    public function handle(CreateMeetingCommand $command): string
    {
        $meeting = new Meeting(
            $this->uuidProvider->generate(),
            $command->getOrganizerId(),
            $command->getName(),
            $command->getLocation(),
            $command->getStartTime()
        );

        $this->meetingService->createMeeting($meeting);

        return $meeting->getUuid();
    }
}