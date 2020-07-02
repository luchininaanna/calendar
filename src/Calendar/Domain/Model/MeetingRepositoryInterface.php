<?php


namespace App\Calendar\Domain\Model;


interface MeetingRepositoryInterface
{
    public function createMeeting(Meeting $meeting): void;
}