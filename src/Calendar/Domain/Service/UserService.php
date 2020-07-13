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
    private UserRepositoryInterface $userRepository;
    private MeetingRepositoryInterface $meetingRepository;
    private MeetingParticipantRepositoryInterface $meetingParticipantRepository;

    public function __construct(MeetingRepositoryInterface $meetingRepository,
                                UserRepositoryInterface $userRepository,
                                MeetingParticipantRepositoryInterface $meetingParticipantRepository)
    {
        $this->userRepository = $userRepository;
        $this->meetingRepository = $meetingRepository;
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
     * @param string $userUuid
     * @throws UserIsNotExistException
     */
    public function deleteUser(string $userUuid): void
    {
        if (!$this->userRepository->isUserExistById($userUuid))
        {
            throw new UserIsNotExistException();
        }

        $this->userRepository->deleteUserById($userUuid);
    }
}