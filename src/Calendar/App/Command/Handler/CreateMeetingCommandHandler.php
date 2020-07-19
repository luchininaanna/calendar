<?php


namespace App\Calendar\App\Command\Handler;


use App\Calendar\App\Command\CreateMeetingCommand;
use App\Calendar\App\Synchronization\SynchronizationInterface;
use App\Calendar\App\Uuid\UuidProviderInterface;
use App\Calendar\Domain\Exception\MeetingOrganizerIsNotExistException;
use App\Calendar\Domain\Model\Meeting;
use App\Calendar\Domain\Service\MeetingService;

class CreateMeetingCommandHandler
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
     * @param CreateMeetingCommand $command
     * @return string
     * @throws MeetingOrganizerIsNotExistException
     */
    public function handle(CreateMeetingCommand $command): string
    {
        return $this->synchronization->transaction(function() use ($command) {
            $meeting = new Meeting(
                $this->uuidProvider->generate(),
                $command->getInvokerId(),
                $command->getName(),
                $command->getLocation(),
                $command->getStartTime()
            );

            $this->meetingService->createMeeting($meeting);

            return $meeting->getId();
        });
    }
}