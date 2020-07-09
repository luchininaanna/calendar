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
    private RequestService $requestService;
    private MeetingParticipantGenerator $meetingParticipantGenerator;
    private ConfirmExistence $confirmExistance;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->userGenerator = new UserGenerator();
        $this->meetingGenerator = new MeetingGenerator();
        $this->requestService = new RequestService();
        $this->meetingParticipantGenerator = new MeetingParticipantGenerator();
        $this->confirmExistance = new ConfirmExistence();
    }

    public function testDeleteUserFromMeeting(): void
    {
        $client = static::createClient();
        $organizerId = $this->getUserId($client);
        $userId = $this->getUserId($client);
        $meetingId = $this->getMeetingId($client, $organizerId);
        $meetingParticipant = $this->meetingParticipantGenerator->createMeetingParticipantModel($organizerId, $meetingId, $userId);
        $this->requestService->sendCreateMeetingParticipantRequest($client, $meetingParticipant);
        //проверка приглашения пользователя
        $this->assertEquals(true, $this->confirmExistance->isMeetingParticipantExist($client, $meetingId, $organizerId, $userId));

        $this->requestService->sendDeleteMeetingParticipantRequest($client, $meetingParticipant);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('User deleted from meeting', $response['result']);
        //проверка отсутствия приглашения у пользователя
        $this->assertEquals(false, $this->confirmExistance->isMeetingParticipantExist($client, $meetingId, $organizerId, $userId));
    }

    public function testDeleteOrganizerFromMeeting(): void
    {
        $client = static::createClient();
        $organizerId = $this->getUserId($client);
        $meetingId = $this->getMeetingId($client, $organizerId);
        $meetingParticipant = $this->meetingParticipantGenerator->createMeetingParticipantModel($organizerId, $meetingId, $organizerId);
        $this->requestService->sendCreateMeetingParticipantRequest($client, $meetingParticipant);

        $this->requestService->sendDeleteMeetingParticipantRequest($client, $meetingParticipant);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('User deleted from meeting', $response['result']);

        //проверка удаления митинга
        $this->assertEquals(false, $this->confirmExistance->isMeetingExist($client, $meetingId, $organizerId));
        //проверка удаления пользователя с митинга
        $this->assertEquals(false, $this->confirmExistance->isMeetingHasParticipants($client, $meetingId, $organizerId));
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