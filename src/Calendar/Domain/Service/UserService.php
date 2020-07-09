<?php


namespace App\Calendar\Domain\Service;


use App\Calendar\Domain\Exception\UserAlreadyExistException;
use App\Calendar\Domain\Exception\UserIsNotExistException;
use App\Calendar\Domain\Model\MeetingParticipantRepositoryInterface;
use App\Calendar\Domain\Model\MeetingRepositoryInterface;
use App\Calendar\Domain\Model\User;
use App\Calendar\Domain\Model\UserRepositoryInterface;

class UserService
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
     * @param User $user
     * @throws UserAlreadyExistException
     */
    public function createUser(User $user): void
    {
        if ($this->userRepository->isUserExistByLogin($user->getLogin()))
        {
            throw new UserAlreadyExistException();
        }

        $this->userRepository->createUser($user);
    }

    /**
     * @param String $userUuid
     * @throws UserIsNotExistException
     */
    public function deleteUser(String $userUuid): void
    {
        if (!$this->userRepository->isUserExistById($userUuid))
        {
            throw new UserIsNotExistException();
        }

        $this->userRepository->deleteUserById($userUuid);
    }
}