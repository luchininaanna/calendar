<?php


namespace App\Calendar\Domain\Service;


use App\Calendar\Domain\Exception\UserAlreadyExistException;
use App\Calendar\Domain\Exception\UserIsNotExistException;
use App\Calendar\Domain\Model\User;
use App\Calendar\Domain\Model\UserRepositoryInterface;

class UserService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository
    ) {
        $this->userRepository = $userRepository;
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
     * @param string $userId
     * @throws UserIsNotExistException
     */
    public function deleteUser(string $userId): void
    {
        if (!$this->userRepository->isUserExistById($userId))
        {
            throw new UserIsNotExistException();
        }

        $this->userRepository->deleteUserById($userId);
    }
}