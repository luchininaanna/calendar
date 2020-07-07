<?php


namespace App\Calendar\App\Query;


use App\Calendar\App\Query\Data\UserData;

interface UserQueryServiceInterface
{
    /**
     * @return UserData[]
     */
    public function getAllUsers(): array;
}