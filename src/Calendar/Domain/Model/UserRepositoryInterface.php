<?php


namespace App\Calendar\Domain\Model;


interface UserRepositoryInterface
{
    public function createUser(User $user): void;
}