<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class ConfirmExistence
{
    private RequestService $requestService;

    public function __construct()
    {
        $this->requestService = new RequestService();
    }

    public function isMeetingExist(KernelBrowser $client, string $meetingId, string $organizerId): bool
    {
        $meetingsJson = $this->requestService->getAllMeetingByOrganizer($client, $organizerId);
        $meetingsArray = json_decode($meetingsJson, true);

        foreach ($meetingsArray as $meeting)
        {
            if ($meeting['uuid'] === $meetingId)
            {
                return true;
            }
        }

        return false;
    }

    public function isMeetingParticipantExist(
        KernelBrowser $client,
        string $meetingId,
        string $organizerId,
        string $userId
    ): bool {
        $meetingParticipantJson = $this->requestService->getAllMeetingParticipantByOrganizer($client, $organizerId, $meetingId);
        $meetingParticipantArray = json_decode($meetingParticipantJson, true);

        foreach ($meetingParticipantArray as $meetingParticipant)
        {
            if ($meetingParticipant['uuid'] === $userId)
            {
                return true;
            }
        }

        return false;
    }

    public function isMeetingHasParticipants(KernelBrowser $client, string $meetingId, string $organizerId): bool
    {
        $meetingParticipantJson = $this->requestService->getAllMeetingParticipantByOrganizer($client, $organizerId, $meetingId);
        $meetingParticipantArray = json_decode($meetingParticipantJson, true);
        $participantAmount = count($meetingParticipantArray);
        return $participantAmount > 0;
    }

    public function isUserExist(KernelBrowser $client, string $userId): bool
    {
        $userJson = $this->requestService->getAllUser($client);
        $userArray = json_decode($userJson, true);

        foreach ($userArray as $user)
        {
            if ($user['uuid'] === $userId)
            {
                return true;
            }
        }

        return false;
    }
}