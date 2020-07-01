<?php


namespace App\Calendar\Api;


use App\Calendar\Api\Exception\UserAlreadyExistException;
use App\Calendar\Api\Input\CreateUserInput;

interface ApiCommandInterface
{
    /**
     * @param CreateUserInput $input
     * @throws UserAlreadyExistException
     */
    public function createUser(CreateUserInput $input): void;
}