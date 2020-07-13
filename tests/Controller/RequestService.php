<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class RequestService
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
            '/meeting/participant/invite',
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
            '/meeting/participant/delete',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($meetingParticipant)
        );
    }

    public function sendDeleteMeeting(KernelBrowser $client, string $loggedUserId, string $meetingId): void
    {
        $client->request(
            'POST',
            '/meeting/delete',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'loggedUserId' => $loggedUserId,
                'meetingId' => $meetingId
            ])
        );
    }

    public function getAllMeetingByOrganizer(KernelBrowser $client, string $organizerId): string
    {
        $client->request(
            'GET',
            '/get/organizer/meetings',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                "loggedUserId" => $organizerId,
            ])
        );

        return $client->getResponse()->getContent();
    }

    public function getAllMeetingParticipantByOrganizer(KernelBrowser $client, string $organizerId, string $meetingId): string
    {
        $client->request(
            'GET',
            '/get/participants/as/organizer',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                "loggedUserId" => $organizerId,
	            "meetingId" => $meetingId
            ])
        );

        return $client->getResponse()->getContent();
    }

    public function getAllUser(KernelBrowser $client): string
    {
        $client->request(
            'GET',
            '/get/users',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            ""
        );

        return $client->getResponse()->getContent();
    }
}