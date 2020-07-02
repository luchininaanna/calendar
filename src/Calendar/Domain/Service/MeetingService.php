<?php


namespace App\Calendar\Domain\Service;


use App\Calendar\Domain\Model\Meeting;
use App\Calendar\Domain\Model\MeetingRepositoryInterface;

class MeetingService
{
    private MeetingRepositoryInterface $meetingRepository;

    public function __construct(MeetingRepositoryInterface $meetingRepository)
    {
        $this->meetingRepository = $meetingRepository;
    }

    /**
     * @param Meeting $meeting
     */
    public function createMeeting(Meeting $meeting): void
    {
        //проверка существования организатора
        $this->meetingRepository->createMeeting($meeting);
    }
}