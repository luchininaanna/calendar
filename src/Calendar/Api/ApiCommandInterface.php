<?php


namespace App\Calendar\Api;


use App\Calendar\Api\Input\CreateUserInput;

interface ApiCommandInterface
{
    public function createUser(CreateUserInput $input): void;
}