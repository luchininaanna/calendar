<?php


namespace App\Tests\Controller;


use App\Calendar\App\Uuid\UuidProviderInterface;
use App\Calendar\Infrastructure\Uuid\UuidProvider;
use App\Tests\Controller\JsonBuilder\MeetingJsonBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateMeetingWriteControllerTest extends WebTestCase
{
    private EntityCreator $entityCreator;
    private RequestService $requestService;
    private UuidProviderInterface $uuidProvider;
    private MeetingJsonBuilder $meetingJsonBuilder;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->entityCreator = new EntityCreator();
        $this->requestService = new RequestService();
        $this->uuidProvider = new UuidProvider();
        $this->meetingJsonBuilder = new MeetingJsonBuilder();
    }

    public function testCreateMeeting(): void
    {
        $client = static::createClient();
        $userId = $this->entityCreator ->getUserId($client);
        $meeting = $this->meetingJsonBuilder->createMeetingJson($userId);

        $this->requestService->sendCreateMeetingRequest($client, $meeting);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(ResponseDescription::MEETING_CREATED, $response['result']);
        $this->assertArrayHasKey('id', $response);
    }

    public function testCreateMeetingWithNotExistOrganizer(): void
    {
        $client = static::createClient();
        $meeting = $this->meetingJsonBuilder->createMeetingJson($this->uuidProvider->generate());

        $this->requestService->sendCreateMeetingRequest($client, $meeting);

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(ResponseDescription::MEETING_ORGANISER_IS_NOT_EXIST, $response['result']);
    }
}