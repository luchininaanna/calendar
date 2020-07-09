<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class RequestSender
{
    public function sendCreateUserRequest(KernelBrowser $client, array $user): void
    {
        $client->request(
            'POST',
            '/user/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($user)
        );
    }

    public function sendDeleteUserRequest(KernelBrowser $client, string $userId): void
    {
        $client->request(
            'POST',
            '/user/delete',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                "userId" => $userId,
            ])
        );
    }

    public function sendCreateMeetingRequest(KernelBrowser $client, array $meeting): void
    {
        $client->request(
            'POST',
            '/meeting/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($meeting)
        );
    }

    public function sendCreateMeetingParticipantRequest(KernelBrowser $client, array $meetingParticipant): void
    {
        $client->request(
            'POST',
            '/meeting/invite',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($meetingParticipant)
        );
    }

    public function sendDeleteMeetingParticipantRequest(KernelBrowser $client, array $meetingParticipant): void
    {
        $client->request(
            'POST',
            '/user/deleteFromMeeting',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($meetingParticipant)
        );
    }
}