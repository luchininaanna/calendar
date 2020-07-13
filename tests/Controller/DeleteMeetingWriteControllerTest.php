<?php


namespace App\Tests\Controller;


use App\Tests\Controller\JsonBuilder\MeetingJsonBuilder;
use App\Tests\Controller\JsonBuilder\MeetingParticipantJsonBuilder;
use App\Tests\Controller\JsonBuilder\UserJsonBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeleteMeetingWriteControllerTest  extends WebTestCase
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

    public function testDeleteUserFromMeeting(): void
    {
        $client = static::createClient();
        $organizerId = $this->entityCreator->getUserId($client);
        $userId = $this->entityCreator->getUserId($client);
        $meetingId = $this->entityCreator->getMeetingId($client, $organizerId);
        $meetingParticipant = $this->meetingParticipantJsonBuilder->
        createMeetingParticipantJson($organizerId, $meetingId, $userId);

        $this->requestService->sendCreateMeetingParticipantRequest($client, $meetingParticipant);

        $this->assertEquals(true, $this->confirmExistence->isMeetingExist($client, $meetingId, $organizerId));
        $this->assertEquals(true, $this->confirmExistence->
        isMeetingParticipantExist($client, $meetingId, $organizerId, $userId));

        $this->requestService->sendDeleteMeeting($client, $organizerId, $meetingId);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($this->responseDescription::MEETING_DELETED, $response['result']);
        $this->assertEquals(false, $this->confirmExistence->isMeetingExist($client, $meetingId, $organizerId));
        $this->assertEquals(false, $this->confirmExistence->
        isMeetingParticipantExist($client, $meetingId, $organizerId, $userId));
    }
}