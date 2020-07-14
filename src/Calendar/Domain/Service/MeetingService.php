<?php


namespace App\Calendar\Domain\Service;


use App\Calendar\Domain\Exception\MeetingIsNotExistException;
use App\Calendar\Domain\Exception\MeetingOrganizerIsNotExistException;
use App\Calendar\Domain\Exception\MeetingParticipantAmountExceedsLimitException;
use App\Calendar\Domain\Exception\UserIsAlreadyMeetingParticipantException;
use App\Calendar\Domain\Exception\UserIsNotExistException;
use App\Calendar\Domain\Exception\UserIsNotMeetingParticipantException;
use App\Calendar\Domain\Exception\UserIsNotMeetingOrganizerException;
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
        if (!$this->userRepository->isUserExistById($meeting->getLoggedUserId()))
        {
            throw new MeetingOrganizerIsNotExistException();
        }

        $this->meetingRepository->createMeeting($meeting);
    }

    /**
     * @param string $loggedUserUuid
     * @param MeetingParticipant $meetingParticipant
     * @throws MeetingParticipantAmountExceedsLimitException
     * @throws UserIsAlreadyMeetingParticipantException
     * @throws UserIsNotMeetingOrganizerException
     * @throws UserIsNotExistException
     */
    public function createMeetingParticipant(string $loggedUserUuid, MeetingParticipant $meetingParticipant): void
    {
        if (!$this->meetingRepository->isUserIsMeetingOrganizer($loggedUserUuid,
            $meetingParticipant->getMeetingUuid()))
        {
            throw new UserIsNotMeetingOrganizerException();
        }

        if ($this->meetingParticipantRepository->
        isMeetingHasNotAcceptableNumberOfParticipants($meetingParticipant->getMeetingUuid()))
        {
            throw new MeetingParticipantAmountExceedsLimitException();
        }

        if (!$this->userRepository->isUserExistById($meetingParticipant->getUserUuid()))
        {
            throw new UserIsNotExistException();
        }

        if($this->meetingParticipantRepository->isUserIsMeetingParticipant($meetingParticipant->getUserUuid(),
            $meetingParticipant->getMeetingUuid()))
        {
            throw new UserIsAlreadyMeetingParticipantException();
        }

        $this->meetingParticipantRepository->createMeetingParticipant($meetingParticipant);
    }

    /**
     * @param string $loggedUserUuid
     * @param MeetingParticipant $meetingParticipant
     * @throws UserIsNotMeetingOrganizerException
     * @throws UserIsNotMeetingParticipantException
     */
    public function deleteMeetingParticipant(
        string $loggedUserUuid,
        MeetingParticipant $meetingParticipant
    ): void {
        if (!$this->meetingRepository->isUserIsMeetingOrganizer($loggedUserUuid,
            $meetingParticipant->getMeetingUuid()))
        {
            throw new UserIsNotMeetingOrganizerException();
        }

        if(!$this->meetingParticipantRepository->isUserIsMeetingParticipant($meetingParticipant->getUserUuid(),
            $meetingParticipant->getMeetingUuid()))
        {
            throw new UserIsNotMeetingParticipantException();
        }

        $this->meetingParticipantRepository->deleteUserFromMeeting($meetingParticipant->getUserUuid(),
            $meetingParticipant->getMeetingUuid());

        if ($this->meetingRepository->isUserIsMeetingOrganizer($meetingParticipant->getUserUuid(),
            $meetingParticipant->getMeetingUuid()))
        {
            $this->meetingParticipantRepository->deleteInvitationByMeetingId($meetingParticipant->getMeetingUuid());
            $this->meetingRepository->deleteMeetingById($meetingParticipant->getMeetingUuid());
        }
    }

    /**
     * @param string $meetingUuid
     * @param string $loggedUserUuid
     * @throws MeetingIsNotExistException
     * @throws UserIsNotMeetingOrganizerException
     */
    public function deleteMeeting(string $meetingUuid, string $loggedUserUuid): void
    {
        if (!$this->meetingRepository->isMeetingExist($meetingUuid))
        {
            throw new MeetingIsNotExistException();
        }

        if (!$this->meetingRepository->isUserIsMeetingOrganizer($loggedUserUuid,
            $meetingUuid))
        {
            throw new UserIsNotMeetingOrganizerException();
        }

        $this->meetingParticipantRepository->deleteInvitationByMeetingId($meetingUuid);
        $this->meetingRepository->deleteMeetingById($meetingUuid);
    }

    public function deleteUserFromMeetings(string $userUuid): void
    {
        $this->meetingParticipantRepository->deleteUserFromMeetings($userUuid);
        $this->meetingRepository->deleteMeetingsByUserAsOrganizer($userUuid);
    }
}