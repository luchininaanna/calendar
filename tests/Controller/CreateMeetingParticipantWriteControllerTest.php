<?php


namespace App\Tests\Controller;


use App\Tests\Controller\Generators\MeetingGenerator;
use App\Tests\Controller\Generators\MeetingParticipantGenerator;
use App\Tests\Controller\Generators\UserGenerator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateMeetingParticipantWriteControllerTest extends WebTestCase
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

    public function testCreateMeetingParticipantForOrganizer(): void
    {
        $client = static::createClient();
        $organizerId = $this->getUserId($client);
        $meetingId = $this->getMeetingId($client, $organizerId);

        $meetingParticipant = $this->meetingParticipantGenerator->createMeetingParticipant($organizerId, $meetingId, $organizerId);
        $this->requestSender->sendCreateMeetingParticipantRequest($client, $meetingParticipant);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Invitation created', $response['result']);
    }

    public function testCreateMeetingParticipantForUser(): void
    {
        $client = static::createClient();
        $organizerId = $this->getUserId($client);
        $userId = $this->getUserId($client);
        $meetingId = $this->getMeetingId($client, $organizerId);

        $meetingParticipant = $this->meetingParticipantGenerator->createMeetingParticipant($organizerId, $meetingId, $userId);
        $this->requestSender->sendCreateMeetingParticipantRequest($client, $meetingParticipant);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Invitation created', $response['result']);
    }

    public function testCreateMeetingWithOverLimitAmountUser(): void
    {
        $client = static::createClient();
        $organizerId = $this->getUserId($client);
        $meetingId = $this->getMeetingId($client, $organizerId);

        for ($i = 0; $i < 11; $i++) {
            $userId = $this->getUserId($client);
            $meetingParticipant = $this->meetingParticipantGenerator->createMeetingParticipant($organizerId, $meetingId, $userId);
            $this->requestSender->sendCreateMeetingParticipantRequest($client, $meetingParticipant);
        }

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Meeting participant amount exceeds limit', $response['result']);
    }

    public function testCreateMeetingParticipantTwice(): void
    {
        $client = static::createClient();
        $organizerId = $this->getUserId($client);
        $userId = $this->getUserId($client);
        $meetingId = $this->getMeetingId($client, $organizerId);

        $meetingParticipant = $this->meetingParticipantGenerator->createMeetingParticipant($organizerId, $meetingId, $userId);

        $this->requestSender->sendCreateMeetingParticipantRequest($client, $meetingParticipant);
        $this->requestSender->sendCreateMeetingParticipantRequest($client, $meetingParticipant);

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('User ia already meeting participant', $response['result']);
    }

    public function testCreateMeetingParticipantWithNotOrganizer(): void
    {
        $client = static::createClient();
        $organizerId = $this->getUserId($client);
        $userId = $this->getUserId($client);
        $meetingId = $this->getMeetingId($client, $organizerId);

        $meetingParticipant = $this->meetingParticipantGenerator->createMeetingParticipant($userId, $meetingId, $userId);
        $this->requestSender->sendCreateMeetingParticipantRequest($client, $meetingParticipant);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('User is not meeting organizer', $response['result']);
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