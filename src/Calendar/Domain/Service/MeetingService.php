<?php


namespace App\Calendar\Domain\Service;


use App\Calendar\Domain\Exception\MeetingOrganizerIsNotExistException;
use App\Calendar\Domain\Exception\MeetingParticipantAmountExceedsLimitException;
use App\Calendar\Domain\Exception\UserIsAlreadyMeetingParticipantException;
use App\Calendar\Domain\Exception\UserIsNotMeetingOrganizerException;
use App\Calendar\Domain\Model\Meeting;
use App\Calendar\Domain\Model\MeetingParticipant;
use App\Calendar\Domain\Model\MeetingParticipantRepositoryInterface;
use App\Calendar\Domain\Model\MeetingRepositoryInterface;
use App\Calendar\Domain\Model\UserRepositoryInterface;
use Symfony\Component\HttpKernel\Log\Logger;

class MeetingService
{
    private MeetingRepositoryInterface $meetingRepository;
    private UserRepositoryInterface $userRepository;
    private MeetingParticipantRepositoryInterface $meetingParticipantRepository;

    public function __construct(MeetingRepositoryInterface $meetingRepository,
                                UserRepositoryInterface $userRepository,
                                MeetingParticipantRepositoryInterface $meetingParticipantRepository)
    {
        $this->meetingRepository = $meetingRepository;
        $this->userRepository = $userRepository;
        $this->meetingParticipantRepository = $meetingParticipantRepository;
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

    /**
     * @param MeetingParticipant $meetingParticipant
     * @throws MeetingParticipantAmountExceedsLimitException
     * @throws UserIsNotMeetingOrganizerException
     * @throws UserIsAlreadyMeetingParticipantException
     */
    public function createMeetingParticipant(MeetingParticipant $meetingParticipant):void
    {
        if ($this->meetingRepository->isUserIsMeetingOrganizer($meetingParticipant->getOrganizerUuid(),
            $meetingParticipant->getMeetingUuid()))
        {
            throw new UserIsNotMeetingOrganizerException();
        }

        if ($this->meetingParticipantRepository->isMeetingHasNotAcceptableNumberOfParticipants($meetingParticipant->getMeetingUuid()))
        {
            throw new MeetingParticipantAmountExceedsLimitException();
        }

        if($this->meetingParticipantRepository->isUserIsMeetingParticipant($meetingParticipant->getUserUuid(), $meetingParticipant->getMeetingUuid()))
        {
            throw new UserIsAlreadyMeetingParticipantException();
        }

        $this->meetingParticipantRepository->createMeetingParticipant($meetingParticipant);
    }
}