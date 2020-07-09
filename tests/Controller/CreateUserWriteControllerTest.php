<?php


namespace App\Tests\Controller;


use App\Tests\Controller\Generators\UserGenerator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateUserWriteControllerTest extends WebTestCase
{
    private UserGenerator $userGenerator;
    private RequestSender $requestSender;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->userGenerator = new UserGenerator();
        $this->requestSender = new RequestSender();
    }

    public function testCreateUniqueUser(): void
    {
        $client = static::createClient();
        $this->requestSender->sendCreateUserRequest($client, $this->userGenerator->createUser());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('User created', $response['result']);
        $this->assertArrayHasKey('id', $response);
    }

    public function testCreateNotUniqueUser(): void
    {
        $client = static::createClient();
        $user = $this->userGenerator->createUser();
        $this->requestSender->sendCreateUserRequest($client, $user);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->requestSender->sendCreateUserRequest($client, $user);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('User already exist', $response['result']);
    }

    public function testCreateUserWithEmptyFields(): void
    {
        $client = static::createClient();
        $this->requestSender->sendCreateUserRequest($client, $this->userGenerator->createUserWithEmptyFields());
        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Empty request parameters', $response['result']);
    }
}