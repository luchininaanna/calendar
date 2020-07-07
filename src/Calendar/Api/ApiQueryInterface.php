<?php


namespace App\Calendar\Api;


use App\Calendar\Api\Output\UserOutput;

interface ApiQueryInterface
{
    /**
     * @return UserOutput[]
     */
    public function getAllUsers(): array;
}