<?php


namespace App\Tests\Controller;


use App\Tests\Controller\JsonBuilder\MeetingParticipantJsonBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateMeetingParticipantWriteControllerTest extends WebTestCase
{
    private EntityCreator $entityCreator;
    private RequestService $requestService;
    private MeetingParticipantJsonBuilder $meetingParticipantJsonBuilder;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->entityCreator = new EntityCreator();
        $this->requestService = new RequestService();
        $this->meetingParticipantJsonBuilder = new MeetingParticipantJsonBuilder();
    }

    public function testCreateMeetingParticipantForOrganizer(): void
    {
        $client = static::createClient();
        $organizerId = $this->entityCreator->getUserId($client);
        $meetingId = $this->entityCreator->getMeetingId($client, $organizerId);

        $meetingParticipant = $this->meetingParticipantJsonBuilder->
        createMeetingParticipantJson($organizerId, $meetingId, $organizerId);
        $this->requestService->sendCreateMeetingParticipantRequest($client, $meetingParticipant);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(ResponseDescription::INVITATION_CREATED, $response['result']);
    }

    public function testCreateMeetingParticipantForUser(): void
    {
        $client = static::createClient();
        $organizerId = $this->entityCreator->getUserId($client);
        $userId = $this->entityCreator->getUserId($client);
        $meetingId = $this->entityCreator->getMeetingId($client, $organizerId);

        $meetingParticipant = $this->meetingParticipantJsonBuilder->
        createMeetingParticipantJson($organizerId, $meetingId, $userId);
        $this->requestService->sendCreateMeetingParticipantRequest($client, $meetingParticipant);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(ResponseDescription::INVITATION_CREATED, $response['result']);
    }

    public function testCreateMeetingWithOverLimitAmountUser(): void
    {
        $client = static::createClient();
        $organizerId = $this->entityCreator->getUserId($client);
        $meetingId = $this->entityCreator->getMeetingId($client, $organizerId);

        for ($i = 0; $i < 11; $i++) {
            $userId = $this->entityCreator->getUserId($client);
            $meetingParticipant = $this->meetingParticipantJsonBuilder->
            createMeetingParticipantJson($organizerId, $meetingId, $userId);
            $this->requestService->sendCreateMeetingParticipantRequest($client, $meetingParticipant);
        }

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(ResponseDescription::MEETING_PARTICIPANT_AMOUNT_EXCEEDS_LIMIT, $response['result']);
    }

    public function testCreateMeetingParticipantTwice(): void
    {
        $client = static::createClient();
        $organizerId = $this->entityCreator->getUserId($client);
        $userId = $this->entityCreator->getUserId($client);
        $meetingId = $this->entityCreator->getMeetingId($client, $organizerId);
        $meetingParticipant = $this->meetingParticipantJsonBuilder->
        createMeetingParticipantJson($organizerId, $meetingId, $userId);

        $this->requestService->sendCreateMeetingParticipantRequest($client, $meetingParticipant);
        $this->requestService->sendCreateMeetingParticipantRequest($client, $meetingParticipant);

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(ResponseDescription::USER_IS_ALREADY_MEETING_PARTICIPANT, $response['result']);
    }

    public function testCreateMeetingParticipantWithNotOrganizer(): void
    {
        $client = static::createClient();
        $organizerId = $this->entityCreator->getUserId($client);
        $userId = $this->entityCreator->getUserId($client);
        $meetingId = $this->entityCreator->getMeetingId($client, $organizerId);
        $meetingParticipant = $this->meetingParticipantJsonBuilder->
        createMeetingParticipantJson($userId, $meetingId, $userId);

        $this->requestService->sendCreateMeetingParticipantRequest($client, $meetingParticipant);

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(ResponseDescription::USER_IS_NOT_MEETING_ORGANIZER, $response['result']);
    }
}