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

        $isExist = false;
        foreach ($meetingsArray as $meeting)
        {
            if ($meeting['uuid'] === $meetingId)
            {
                $isExist = true;
            }
        }

        return $isExist;
    }

    public function isMeetingParticipantExist(KernelBrowser $client, string $meetingId,
                                               string $organizerId, string $userId): bool
    {
        $meetingParticipantJson = $this->requestService->getAllMeetingParticipantByOrganizer($client, $organizerId, $meetingId);
        $meetingParticipantArray = json_decode($meetingParticipantJson, true);

        $isExist = false;
        foreach ($meetingParticipantArray as $meetingParticipant)
        {
            if ($meetingParticipant['uuid'] === $userId)
            {
                $isExist = true;
            }
        }

        return $isExist;
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

        $isExist = false;
        foreach ($userArray as $user)
        {
            if ($user['uuid'] === $userId)
            {
                $isExist = true;
            }
        }

        return $isExist;
    }
}