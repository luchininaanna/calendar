<?php


namespace App\Tests\Controller;


use App\Tests\Controller\JsonBuilder\UserJsonBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateUserWriteControllerTest extends WebTestCase
{
    private RequestService $requestService;
    private UserJsonBuilder $userJsonBuilder ;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->requestService = new RequestService();
        $this->userJsonBuilder = new UserJsonBuilder();
    }

    public function testCreateUniqueUser(): void
    {
        $client = static::createClient();

        $this->requestService->sendCreateUserRequest($client, $this->userJsonBuilder->createUserJson());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('User created', $response['result']);
        $this->assertArrayHasKey('id', $response);
    }

    public function testCreateNotUniqueUser(): void
    {
        $client = static::createClient();
        $user = $this->userJsonBuilder->createUserJson();

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

        $this->requestService->sendCreateUserRequest($client, $this->userJsonBuilder->createUserWithEmptyFieldsJson());

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Empty request parameters', $response['result']);
    }
}