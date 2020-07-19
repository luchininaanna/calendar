<?php


namespace App\Calendar\Domain\Service;


use App\Calendar\Domain\Exception\MeetingIsNotExistException;
use App\Calendar\Domain\Exception\MeetingOrganizerIsNotExistException;
use App\Calendar\Domain\Exception\MeetingParticipantAmountExceedsLimitException;
use App\Calendar\Domain\Exception\MeetingParticipantIsAlreadyExistException;
use App\Calendar\Domain\Exception\UserIsNotExistException;
use App\Calendar\Domain\Exception\MeetingParticipantIsNotCorrectException;
use App\Calendar\Domain\Exception\MeetingOrganizerIsNotCorrectException;
use App\Calendar\Domain\Model\Meeting;
use App\Calendar\Domain\Model\MeetingParticipant;
use App\Calendar\Domain\Model\MeetingParticipantRepositoryInterface;
use App\Calendar\Domain\Model\MeetingRepositoryInterface;
use App\Calendar\Domain\Model\UserRepositoryInterface;

class MeetingService
{
    private UserRepositoryInterface $userRepository;
    private MeetingRepositoryInterface $meetingRepository;
    private MeetingParticipantRepositoryInterface $meetingParticipantRepository;

    public function __construct(
        MeetingRepositoryInterface $meetingRepository,
        UserRepositoryInterface $userRepository,
        MeetingParticipantRepositoryInterface $meetingParticipantRepository
    ) {
        $this->userRepository = $userRepository;
        $this->meetingRepository = $meetingRepository;
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
     * @param string $loggedUserUuid
     * @param MeetingParticipant $meetingParticipant
     * @throws MeetingParticipantAmountExceedsLimitException
     * @throws MeetingParticipantIsAlreadyExistException
     * @throws MeetingOrganizerIsNotCorrectException
     * @throws UserIsNotExistException
     */
    public function createMeetingParticipant(string $loggedUserUuid, MeetingParticipant $meetingParticipant): void
    {
        if (!$this->meetingRepository->isMeetingOrganizer($loggedUserUuid,
            $meetingParticipant->getMeetingId()))
        {
            throw new MeetingOrganizerIsNotCorrectException();
        }

        if ($this->meetingParticipantRepository->
        isMeetingHasNotAcceptableNumberOfParticipants($meetingParticipant->getMeetingId()))
        {
            throw new MeetingParticipantAmountExceedsLimitException();
        }

        if (!$this->userRepository->isUserExistById($meetingParticipant->getParticipantId()))
        {
            throw new UserIsNotExistException();
        }

        if($this->meetingParticipantRepository->isMeetingParticipant($meetingParticipant->getParticipantId(),
            $meetingParticipant->getMeetingId()))
        {
            throw new MeetingParticipantIsAlreadyExistException();
        }

        $this->meetingParticipantRepository->createMeetingParticipant($meetingParticipant);
    }

    /**
     * @param string $loggedUserUuid
     * @param MeetingParticipant $meetingParticipant
     * @throws MeetingOrganizerIsNotCorrectException
     * @throws MeetingParticipantIsNotCorrectException
     */
    public function deleteMeetingParticipant(
        string $loggedUserUuid,
        MeetingParticipant $meetingParticipant
    ): void {
        if (!$this->meetingRepository->isMeetingOrganizer($loggedUserUuid,
            $meetingParticipant->getMeetingId()))
        {
            throw new MeetingOrganizerIsNotCorrectException();
        }

        if(!$this->meetingParticipantRepository->isMeetingParticipant($meetingParticipant->getParticipantId(),
            $meetingParticipant->getMeetingId()))
        {
            throw new MeetingParticipantIsNotCorrectException();
        }

        $this->meetingParticipantRepository->deleteMeetingParticipant($meetingParticipant->getParticipantId(),
            $meetingParticipant->getMeetingId());
    }

    /**
     * @param string $meetingUuid
     * @param string $loggedUserUuid
     * @throws MeetingIsNotExistException
     * @throws MeetingOrganizerIsNotCorrectException
     */
    public function deleteMeeting(string $meetingUuid, string $loggedUserUuid): void
    {
        if (!$this->meetingRepository->isMeetingExist($meetingUuid))
        {
            throw new MeetingIsNotExistException();
        }

        if (!$this->meetingRepository->isMeetingOrganizer($loggedUserUuid,
            $meetingUuid))
        {
            throw new MeetingOrganizerIsNotCorrectException();
        }

        $this->meetingParticipantRepository->deleteAllMeetingParticipants($meetingUuid);
        $this->meetingRepository->deleteMeetingById($meetingUuid);
    }

    public function deleteParticipantFromAllMeetings(string $userUuid): void
    {
        $this->meetingParticipantRepository->deleteParticipantFromAllMeetings($userUuid);
    }
}