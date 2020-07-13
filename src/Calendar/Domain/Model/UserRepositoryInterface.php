<?php


namespace App\Calendar\Domain\Model;


interface UserRepositoryInterface
{
    public function createUser(User $user): void;

    public function isUserExistById(string $uuid): bool;

    public function isUserExistByLogin(string $login): bool;
}