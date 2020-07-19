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
    private const PARTICIPANT_LIMIT = 10;

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
     * @param string $invokerId
     * @param MeetingParticipant $meetingParticipant
     * @throws MeetingParticipantAmountExceedsLimitException
     * @throws MeetingParticipantIsAlreadyExistException
     * @throws MeetingOrganizerIsNotCorrectException
     * @throws UserIsNotExistException
     */
    public function createMeetingParticipant(string $invokerId, MeetingParticipant $meetingParticipant): void
    {
        if (!$this->meetingRepository->isMeetingOrganizer($invokerId,
            $meetingParticipant->getMeetingId()))
        {
            throw new MeetingOrganizerIsNotCorrectException();
        }

        $meetingParticipantAmount = $this->meetingParticipantRepository->
            getMeetingParticipantAmount($meetingParticipant->getMeetingId());

        if ($meetingParticipantAmount >= MeetingService::PARTICIPANT_LIMIT)
        {
            throw new MeetingParticipantAmountExceedsLimitException();
        }

        if (!$this->userRepository->isUserExistById($meetingParticipant->getParticipantId()))
        {
            throw new UserIsNotExistException();
        }

        if ($this->meetingParticipantRepository->isMeetingParticipant($meetingParticipant->getParticipantId(),
            $meetingParticipant->getMeetingId()))
        {
            throw new MeetingParticipantIsAlreadyExistException();
        }

        $this->meetingParticipantRepository->createMeetingParticipant($meetingParticipant);
    }

    /**
     * @param string $invokerId
     * @param string $meetingId
     * @param string $participantId
     * @throws MeetingOrganizerIsNotCorrectException
     * @throws MeetingParticipantIsNotCorrectException
     */
    public function deleteMeetingParticipant(
        string $invokerId,
        string $meetingId,
        string $participantId
    ): void {
        if (!$this->meetingRepository->isMeetingOrganizer($invokerId, $meetingId))
        {
            throw new MeetingOrganizerIsNotCorrectException();
        }

        if (!$this->meetingParticipantRepository->isMeetingParticipant($participantId, $meetingId))
        {
            throw new MeetingParticipantIsNotCorrectException();
        }

        $this->meetingParticipantRepository->deleteMeetingParticipant($participantId, $meetingId);
    }

    /**
     * @param string $meetingUuid
     * @param string $invokerId
     * @throws MeetingIsNotExistException
     * @throws MeetingOrganizerIsNotCorrectException
     */
    public function deleteMeeting(string $meetingUuid, string $invokerId): void
    {
        if (!$this->meetingRepository->isMeetingExist($meetingUuid))
        {
            throw new MeetingIsNotExistException();
        }

        if (!$this->meetingRepository->isMeetingOrganizer($invokerId,
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

    public function deleteAllMeetingsForOrganizer(string $userUuid): void
    {
        $this->meetingRepository->deleteMeetingsForOrganizer($userUuid);
    }
}