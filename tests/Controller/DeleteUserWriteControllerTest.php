<?php


namespace App\Tests\Controller;


use App\Tests\Controller\Generators\MeetingGenerator;
use App\Tests\Controller\Generators\MeetingParticipantGenerator;
use App\Tests\Controller\Generators\UserGenerator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeleteUserWriteControllerTest extends WebTestCase
{
    private MeetingGenerator $meetingGenerator;
    private UserGenerator $userGenerator;
    private RequestService $requestService;
    private MeetingParticipantGenerator $meetingParticipantGenerator;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->userGenerator = new UserGenerator();
        $this->meetingGenerator = new MeetingGenerator();
        $this->requestService = new RequestService();
        $this->meetingParticipantGenerator = new MeetingParticipantGenerator();
    }

    public function testDeleteUser(): void
    {
        $client = static::createClient();
        $organizerId = $this->getUserId($client);
        $userId = $this->getUserId($client);
        $meetingId = $this->getMeetingId($client, $organizerId);
        $meetingParticipant = $this->meetingParticipantGenerator->createMeetingParticipantModel($organizerId, $meetingId, $userId);
        $this->requestService->sendCreateMeetingParticipantRequest($client, $meetingParticipant);

        //проверка существования пользователя
        $this->assertEquals(true, $this->isUserExist($client, $userId));

        $this->requestService->sendDeleteUserRequest($client, $userId);

        //проверка отсутствия пользователя
        $this->assertEquals(false, $this->isUserExist($client, $userId));
        //проверка отсутсвия записей на митинг для пользователя
        $this->assertEquals(false, $this->isMeetingParticipantExist($client, $meetingId, $organizerId, $userId));
    }

    public function testDeleteOrganizerMeetingUser(): void
    {
        $client = static::createClient();
        $organizerId = $this->getUserId($client);
        $userId = $this->getUserId($client);
        $meetingId = $this->getMeetingId($client, $organizerId);
        $meetingParticipant = $this->meetingParticipantGenerator->createMeetingParticipantModel($organizerId, $meetingId, $userId);
        $this->requestService->sendCreateMeetingParticipantRequest($client, $meetingParticipant);

        //проверка существования пользователя
        $this->assertEquals(true, $this->isUserExist($client, $organizerId));
        //проверка существования митинга
        $this->assertEquals(true, $this->isMeetingExist($client, $meetingId, $organizerId));

        $this->requestService->sendDeleteUserRequest($client, $organizerId);

        //проверка удаления пользователя
        $this->assertEquals(false, $this->isUserExist($client, $organizerId));
        //проверка удаления пользователей из митинга
        $this->assertEquals(false, $this->isMeetingHasParticipants($client, $meetingId, $organizerId));
        //проверка удаления митинга
        $this->assertEquals(false, $this->isMeetingExist($client, $meetingId, $organizerId));
    }

    public function testDeleteNotExistUser(): void
    {
        $client = static::createClient();
        $this->requestService->sendDeleteUserRequest($client, "0da175e1-11fd-4bed-b3f1-40deaffb43c1");

        $statusCode = $client->getResponse()->getStatusCode();
        $this->assertEquals(400, $statusCode);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('User is not exist', $response['result']);
    }

    private function isMeetingExist(KernelBrowser $client, string $meetingId, string $organizerId): bool
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

    private function isMeetingParticipantExist(KernelBrowser $client, string $meetingId,
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

    private function isMeetingHasParticipants(KernelBrowser $client, string $meetingId, string $organizerId): bool
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

    private function getUserId(KernelBrowser $client): string
    {
        $this->requestService->sendCreateUserRequest($client, $this->userGenerator->createUserModel());
        $response = json_decode($client->getResponse()->getContent(), true);
        return $response['id'];
    }

    private function getMeetingId(KernelBrowser $client, string $organizerId): string
    {
        $meeting = $this->meetingGenerator->createMeetingModel($organizerId);
        $this->requestService->sendCreateMeetingRequest($client, $meeting);
        $response = json_decode($client->getResponse()->getContent(), true);
        return $response['id'];
    }
}