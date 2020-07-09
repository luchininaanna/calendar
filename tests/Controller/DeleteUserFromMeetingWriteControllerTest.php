<?php


namespace App\Tests\Controller;


use App\Tests\Controller\Generators\MeetingGenerator;
use App\Tests\Controller\Generators\MeetingParticipantGenerator;
use App\Tests\Controller\Generators\UserGenerator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Log\Logger;

class DeleteUserFromMeetingWriteControllerTest extends WebTestCase
{
    private MeetingGenerator $meetingGenerator;
    private UserGenerator $userGenerator;
    private RequestSender $requestSender;
    private MeetingParticipantGenerator $meetingParticipantGenerator;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->userGenerator = new UserGenerator();
        $this->meetingGenerator = new MeetingGenerator();
        $this->requestSender = new RequestSender();
        $this->meetingParticipantGenerator = new MeetingParticipantGenerator();
    }

    public function testDeleteUserFromMeeting(): void
    {
        $client = static::createClient();
        $organizerId = $this->getUserId($client);
        $userId = $this->getUserId($client);
        $meetingId = $this->getMeetingId($client, $organizerId);
        $meetingParticipant = $this->meetingParticipantGenerator->createMeetingParticipant($organizerId, $meetingId, $userId);
        $this->requestSender->sendCreateMeetingParticipantRequest($client, $meetingParticipant);
        //проверка приглашения пользователя
        $this->assertEquals(true, $this->isMeetingParticipantExist($client, $meetingId, $organizerId, $userId));

        $this->requestSender->sendDeleteMeetingParticipantRequest($client, $meetingParticipant);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('User deleted from meeting', $response['result']);
        //проверка отсутствия приглашения у пользователя
        $this->assertEquals(false, $this->isMeetingParticipantExist($client, $meetingId, $organizerId, $userId));
    }

    public function testDeleteOrganizerFromMeeting(): void
    {
        $client = static::createClient();
        $organizerId = $this->getUserId($client);
        $meetingId = $this->getMeetingId($client, $organizerId);
        $meetingParticipant = $this->meetingParticipantGenerator->createMeetingParticipant($organizerId, $meetingId, $organizerId);
        $this->requestSender->sendCreateMeetingParticipantRequest($client, $meetingParticipant);

        $this->requestSender->sendDeleteMeetingParticipantRequest($client, $meetingParticipant);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('User deleted from meeting', $response['result']);

        //проверка удаления митинга
        $this->assertEquals(false, $this->isMeetingExist($client, $meetingId, $organizerId));
        //проверка удаления пользователей из митинга
        $this->assertEquals(false, $this->isMeetingHasParticipants($client, $meetingId, $organizerId));
    }

    private function isMeetingExist(KernelBrowser $client, string $meetingId, string $organizerId): bool
    {
        $meetingsJson = $this->requestSender->getAllMeetingByOrganizer($client, $organizerId);
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

    private function isMeetingParticipantExist(KernelBrowser $client, string $meetingId,
                                               string $organizerId, string $userId): bool
    {
        $meetingParticipantJson = $this->requestSender->getAllMeetingParticipantByOrganizer($client, $organizerId, $meetingId);
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

    private function isMeetingHasParticipants(KernelBrowser $client, string $meetingId, string $organizerId): bool
    {
        $meetingParticipantJson = $this->requestSender->getAllMeetingParticipantByOrganizer($client, $organizerId, $meetingId);
        $meetingParticipantArray = json_decode($meetingParticipantJson, true);
        $participantAmount = count($meetingParticipantArray);
        return $participantAmount > 0;
    }

    private function getUserId(KernelBrowser $client): string
    {
        $this->requestSender->sendCreateUserRequest($client, $this->userGenerator->createUser());
        $response = json_decode($client->getResponse()->getContent(), true);
        return $response['id'];
    }

    private function getMeetingId(KernelBrowser $client, string $organizerId): string
    {
        $meeting = $this->meetingGenerator->createMeeting($organizerId);
        $this->requestSender->sendCreateMeetingRequest($client, $meeting);
        $response = json_decode($client->getResponse()->getContent(), true);
        return $response['id'];
    }
}