<?php


namespace App\Calendar\Domain\Model;


interface UserRepositoryInterface
{
    public function createUser(User $user): void;

    public function isUserExistByLogin(string $login): bool;
}