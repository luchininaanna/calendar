<?php


namespace App\Tests\Controller;


use App\Tests\Controller\Generators\UserGenerator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeleteUserWriteControllerTest extends WebTestCase
{
    private UserGenerator $userGenerator;
    private RequestSender $requestSender;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->userGenerator = new UserGenerator();
        $this->requestSender = new RequestSender();
    }

    public function testDeleteUser(): void
    {
        $client = static::createClient();
        $this->requestSender->sendCreateUserRequest($client, $this->userGenerator->createUser());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->requestSender->sendDeleteUserRequest($client, $response['id']);

        $statusCode = $client->getResponse()->getStatusCode();
        $this->assertEquals(200, $statusCode);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('User deleted', $response['result']);
    }

    public function testDeleteNotExistUser(): void
    {
        $client = static::createClient();
        $this->requestSender->sendDeleteUserRequest($client, "0da175e1-11fd-4bed-b3f1-40deaffb43c1");

        $statusCode = $client->getResponse()->getStatusCode();
        $this->assertEquals(400, $statusCode);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('User is not exist', $response['result']);
    }

    public function testDeleteOrganizerMeetingUser(): void
    {
        //проверка удаления митинга
        //проверка удаления записей на митинг
    }
}