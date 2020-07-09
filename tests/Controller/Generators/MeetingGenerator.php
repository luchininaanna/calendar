<?php


namespace App\Tests\Controller\Generators;


class MeetingGenerator
{
    public function createMeetingModel(string $userId): array
    {
        return [
            "loggedUserId" => $userId,
            "name" => "name",
            "location" => "location",
            "startTime" => "2020-08-06 18:30:30",
        ];
    }
}