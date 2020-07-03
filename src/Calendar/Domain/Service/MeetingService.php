<?php


namespace App\Calendar\Domain\Service;


use App\Calendar\Domain\Exception\MeetingOrganizerIsNotExistException;
use App\Calendar\Domain\Model\Meeting;
use App\Calendar\Domain\Model\MeetingRepositoryInterface;
use App\Calendar\Domain\Model\UserRepositoryInterface;

class MeetingService
{
    private MeetingRepositoryInterface $meetingRepository;
    private UserRepositoryInterface $userRepository;

    public function __construct(MeetingRepositoryInterface $meetingRepository, UserRepositoryInterface $userRepository)
    {
        $this->meetingRepository = $meetingRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param Meeting $meeting
     * @throws MeetingOrganizerIsNotExistException
     */
    public function createMeeting(Meeting $meeting): void
    {
        if (!$this->userRepository->isUserExistById($meeting->getOrganizerId()))
        {
            throw new MeetingOrganizerIsNotExistException();
        }

        $this->meetingRepository->createMeeting($meeting);
    }
}