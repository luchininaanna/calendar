<?php


namespace App\Calendar\Domain\Service;


use App\Calendar\Domain\Exception\MeetingIsNotExistException;
use App\Calendar\Domain\Exception\MeetingOrganizerIsNotExistException;
use App\Calendar\Domain\Exception\MeetingParticipantAmountExceedsLimitException;
use App\Calendar\Domain\Exception\UserIsAlreadyMeetingParticipantException;
use App\Calendar\Domain\Exception\UserIsNotMeetingParticipantException;
use App\Calendar\Domain\Exception\UserIsNotMeetingOrganizerException;
use App\Calendar\Domain\Model\Meeting;
use App\Calendar\Domain\Model\MeetingParticipant;
use App\Calendar\Domain\Model\MeetingParticipantRepositoryInterface;
use App\Calendar\Domain\Model\MeetingRepositoryInterface;
use App\Calendar\Domain\Model\UserRepositoryInterface;

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

        if($this->meetingParticipantRepository->isUserIsMeetingParticipant($meetingParticipant->getUserUuid(),
            $meetingParticipant->getMeetingUuid()))
        {
            throw new UserIsAlreadyMeetingParticipantException();
        }

        $this->meetingParticipantRepository->createMeetingParticipant($meetingParticipant);
    }

    /**
     * @param MeetingParticipant $meetingParticipant
     * @throws UserIsNotMeetingOrganizerException
     * @throws UserIsNotMeetingParticipantException
     */
    public function deleteUserFromMeeting(MeetingParticipant $meetingParticipant):void
    {
        if ($this->meetingRepository->isUserIsMeetingOrganizer($meetingParticipant->getOrganizerUuid(),
            $meetingParticipant->getMeetingUuid()))
        {
            throw new UserIsNotMeetingOrganizerException();
        }

        if(!$this->meetingParticipantRepository->isUserIsMeetingParticipant($meetingParticipant->getUserUuid(),
            $meetingParticipant->getMeetingUuid()))
        {
            throw new UserIsNotMeetingParticipantException();
        }

        $this->meetingParticipantRepository->deleteUserFromMeeting($meetingParticipant->getUserUuid(), $meetingParticipant->getMeetingUuid());

        if ($this->meetingRepository->isUserIsMeetingOrganizer($meetingParticipant->getUserUuid(),
            $meetingParticipant->getMeetingUuid()))
        {
            $this->meetingRepository->deleteMeetingById($meetingParticipant->getMeetingUuid());
        }
    }

    /**
     * @param Meeting $meeting
     * @throws UserIsNotMeetingOrganizerException
     * @throws MeetingIsNotExistException
     */
    public function deleteMeeting(Meeting $meeting): void
    {
        if (!$this->meetingRepository->isMeetingExist($meeting->getUuid()))
        {
            throw new MeetingIsNotExistException();
        }

        if ($this->meetingRepository->isUserIsMeetingOrganizer($meeting->getOrganizerId(),
            $meeting->getUuid()))
        {
            throw new UserIsNotMeetingOrganizerException();
        }

        $this->meetingRepository->deleteMeetingById($meeting->getUuid());
    }

    public function deleteUserFromMeetings(string $userUuid): void
    {
        //удаление пользователя как участника
        $this->meetingParticipantRepository->deleteUserFromMeetings($userUuid);

        //удаление пользователя как организатора
        $this->meetingRepository->deleteMeetingsByUserAsOrganizer($userUuid);
    }
}