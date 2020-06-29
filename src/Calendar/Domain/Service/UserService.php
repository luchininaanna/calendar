<?php


namespace App\Calendar\Domain\Service;


use App\Calendar\Domain\Model\User;
use App\Calendar\Domain\Model\UserRepositoryInterface;

class UserService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(User $user): void
    {
        $this->userRepository->createUser($user);
    }
}