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

    public function getAllMeetingByOrganizer(KernelBrowser $client, string $organizerId): string
    {
        $client->request(
            'GET',
            '/get/meetingsWithOrganizer',
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
            '/get/participantsWithOrganizer',
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
}