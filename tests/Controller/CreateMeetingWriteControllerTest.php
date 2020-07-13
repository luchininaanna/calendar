<?php


namespace App\Tests\Controller;


use App\Tests\Controller\JsonBuilder\MeetingJsonBuilder;
use App\Tests\Controller\JsonBuilder\UserJsonBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateMeetingWriteControllerTest extends WebTestCase
{
    private EntityCreator $entityCreator;
    private RequestService $requestService;
    private UserJsonBuilder $userJsonBuilder;
    private MeetingJsonBuilder $meetingJsonBuilder;
    private ResponseDescription $responseDescription;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->entityCreator = new EntityCreator();
        $this->requestService = new RequestService();
        $this->userJsonBuilder = new UserJsonBuilder();
        $this->meetingJsonBuilder = new MeetingJsonBuilder();
        $this->responseDescription = new ResponseDescription();
    }

    public function testCreateMeeting(): void
    {
        $client = static::createClient();
        $userId = $this->entityCreator ->getUserId($client);
        $meeting = $this->meetingJsonBuilder->createMeetingJson($userId);

        $this->requestService->sendCreateMeetingRequest($client, $meeting);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($this->responseDescription::MEETING_CREATED, $response['result']);
        $this->assertArrayHasKey('id', $response);
    }

    public function testCreateMeetingWithNotExistOrganizer(): void
    {
        $client = static::createClient();
        $meeting = $this->meetingJsonBuilder->createMeetingJson("0da175e1-11fd-4bed-b3f1-40deaffb43c1");

        $this->requestService->sendCreateMeetingRequest($client, $meeting);

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($this->responseDescription::MEETING_ORGANISER_IS_NOT_EXIST, $response['result']);
    }
}