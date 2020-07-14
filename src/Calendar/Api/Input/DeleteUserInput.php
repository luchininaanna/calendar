<?php


namespace App\Calendar\Api\Input;


class DeleteUserInput
{
    private string $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}