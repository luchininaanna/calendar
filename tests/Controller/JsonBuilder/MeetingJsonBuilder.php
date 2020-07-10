<?php


namespace App\Tests\Controller\JsonBuilder;


class MeetingJsonBuilder
{
    public function createMeetingJson(string $userId): array
    {
        return [
            "loggedUserId" => $userId,
            "name" => "name",
            "location" => "location",
            "startTime" => "2020-08-06 18:30:30",
        ];
    }
}