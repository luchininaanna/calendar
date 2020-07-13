<?php


namespace App\Tests\Controller;


use App\Tests\Controller\JsonBuilder\MeetingJsonBuilder;
use App\Tests\Controller\JsonBuilder\MeetingParticipantJsonBuilder;
use App\Tests\Controller\JsonBuilder\UserJsonBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeleteUserWriteControllerTest extends WebTestCase
{
    private EntityCreator $entityCreator;
    private RequestService $requestService;
    private UserJsonBuilder $userJsonBuilder;
    private ConfirmExistence $confirmExistence;
    private MeetingJsonBuilder $meetingJsonBuilder;
    private ResponseDescription $responseDescription;
    private MeetingParticipantJsonBuilder $meetingParticipantJsonBuilder;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->entityCreator = new EntityCreator();
        $this->requestService = new RequestService();
        $this->userJsonBuilder = new UserJsonBuilder();
        $this->confirmExistence = new ConfirmExistence();
        $this->meetingJsonBuilder = new MeetingJsonBuilder();
        $this->responseDescription = new ResponseDescription();
        $this->meetingParticipantJsonBuilder = new MeetingParticipantJsonBuilder();
    }

    public function testDeleteUser(): void
    {
        $client = static::createClient();
        $organizerId = $this->entityCreator->getUserId($client);
        $userId = $this->entityCreator->getUserId($client);
        $meetingId = $this->entityCreator->getMeetingId($client, $organizerId);
        $meetingParticipant = $this->meetingParticipantJsonBuilder->
        createMeetingParticipantJson($organizerId, $meetingId, $userId);

        $this->requestService->sendCreateMeetingParticipantRequest($client, $meetingParticipant);

        $this->assertEquals(true, $this->confirmExistence->isUserExist($client, $userId));

        $this->requestService->sendDeleteUserRequest($client, $userId);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($this->responseDescription::USER_DELETED, $response['result']);
        $this->assertEquals(false, $this->confirmExistence->isUserExist($client, $userId));
        $this->assertEquals(false, $this->confirmExistence->
        isMeetingParticipantExist($client, $meetingId, $organizerId, $userId));
    }

    public function testDeleteOrganizerMeetingUser(): void
    {
        $client = static::createClient();
        $organizerId = $this->entityCreator->getUserId($client);
        $userId = $this->entityCreator->getUserId($client);
        $meetingId = $this->entityCreator->getMeetingId($client, $organizerId);
        $meetingParticipant = $this->meetingParticipantJsonBuilder->
        createMeetingParticipantJson($organizerId, $meetingId, $userId);

        $this->requestService->sendCreateMeetingParticipantRequest($client, $meetingParticipant);

        $this->assertEquals(true, $this->confirmExistence->isUserExist($client, $organizerId));
        $this->assertEquals(true, $this->confirmExistence->isMeetingExist($client, $meetingId, $organizerId));

        $this->requestService->sendDeleteUserRequest($client, $organizerId);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($this->responseDescription::USER_DELETED, $response['result']);
        $this->assertEquals(false, $this->confirmExistence->isUserExist($client, $organizerId));
        $this->assertEquals(false, $this->confirmExistence->
        isMeetingHasParticipants($client, $meetingId, $organizerId));
        $this->assertEquals(false, $this->confirmExistence->isMeetingExist($client, $meetingId, $organizerId));
    }

    public function testDeleteNotExistUser(): void
    {
        $client = static::createClient();

        $this->requestService->sendDeleteUserRequest($client, "0da175e1-11fd-4bed-b3f1-40deaffb43c1");

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($this->responseDescription::USER_IS_NOT_EXIST, $response['result']);
    }
}