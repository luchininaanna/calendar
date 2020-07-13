<?php


namespace App\Tests\Controller;


use App\Tests\Controller\JsonBuilder\UserJsonBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateUserWriteControllerTest extends WebTestCase
{
    private RequestService $requestService;
    private UserJsonBuilder $userJsonBuilder ;
    private ResponseDescription $responseDescription;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->requestService = new RequestService();
        $this->userJsonBuilder = new UserJsonBuilder();
        $this->responseDescription = new ResponseDescription();
    }

    public function testCreateUniqueUser(): void
    {
        $client = static::createClient();

        $this->requestService->sendCreateUserRequest($client, $this->userJsonBuilder->createUserJson());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($this->responseDescription::USER_CREATED, $response['result']);
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
        $this->assertEquals($this->responseDescription::USER_ALREADY_EXIST, $response['result']);
    }

    public function testCreateUserWithEmptyFields(): void
    {
        $client = static::createClient();

        $this->requestService->sendCreateUserRequest($client, $this->userJsonBuilder->createUserWithEmptyFieldsJson());

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($this->responseDescription::EMPTY_REQUEST_PARAMETERS, $response['result']);
    }
}