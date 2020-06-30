<?php


namespace App\Calendar\Domain\Model;


interface UserRepositoryInterface
{
    //проверка существования пользователя с новым логином
    public function createUser(User $user): void;
}