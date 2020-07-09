<?php


namespace App\Tests\Controller;


use App\Tests\Controller\Generators\UserGenerator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateUserWriteControllerTest extends WebTestCase
{
    private UserGenerator $userGenerator;
    private RequestService $requestService;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->userGenerator = new UserGenerator();
        $this->requestService = new RequestService();
    }

    public function testCreateUniqueUser(): void
    {
        $client = static::createClient();
        $this->requestService->sendCreateUserRequest($client, $this->userGenerator->createUserModel());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('User created', $response['result']);
        $this->assertArrayHasKey('id', $response);
    }

    public function testCreateNotUniqueUser(): void
    {
        $client = static::createClient();
        $user = $this->userGenerator->createUserModel();
        $this->requestService->sendCreateUserRequest($client, $user);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->requestService->sendCreateUserRequest($client, $user);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('User already exist', $response['result']);
    }

    public function testCreateUserWithEmptyFields(): void
    {
        $client = static::createClient();
        $this->requestService->sendCreateUserRequest($client, $this->userGenerator->createUserWithEmptyFieldsModel());
        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Empty request parameters', $response['result']);
    }
}