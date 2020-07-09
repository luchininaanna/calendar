<?php


namespace App\Tests\Controller;


use App\Kernel;
use App\Tests\Controller\Generators\MeetingGenerator;
use App\Tests\Controller\Generators\UserGenerator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateMeetingWriteControllerTest extends WebTestCase
{
    private MeetingGenerator $meetingGenerator;
    private UserGenerator $userGenerator;
    private RequestSender $requestSender;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->userGenerator = new UserGenerator();
        $this->meetingGenerator = new MeetingGenerator();
        $this->requestSender = new RequestSender();
    }

    public function testCreateMeeting(): void
    {
        $client = static::createClient();
        $userId = $this->getUserId($client);

        $meeting = $this->meetingGenerator->createMeeting($userId);
        $this->requestSender->sendCreateMeetingRequest($client, $meeting);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Meeting created', $response['result']);
        $this->assertArrayHasKey('id', $response);
    }

    public function testCreateMeetingWithNotExistOrganizer(): void
    {
        $client = static::createClient();

        $meeting = $this->meetingGenerator->createMeeting("0da175e1-11fd-4bed-b3f1-40deaffb43c1");
        $this->requestSender->sendCreateMeetingRequest($client, $meeting);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Meeting organizer is not exist', $response['result']);
    }

    private function getUserId(KernelBrowser $client): string
    {
        $this->requestSender->sendCreateUserRequest($client, $this->userGenerator->createUser());
        $response = json_decode($client->getResponse()->getContent(), true);
        return $response['id'];
    }
}