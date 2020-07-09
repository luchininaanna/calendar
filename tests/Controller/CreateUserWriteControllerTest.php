<?php


namespace App\Tests\Controller;


use App\Tests\Controller\Generators\UserGenerator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateUserWriteControllerTest extends WebTestCase
{
    private UserGenerator $userGenerator;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->userGenerator = new UserGenerator();
    }

    public function testCreateUniqueUser(): void
    {
        $client = static::createClient();
        $this->sendRequest($client, $this->userGenerator->createRandomJsonUser());
        $responseContent = $client->getResponse()->getContent();

        $statusCode = $client->getResponse()->getStatusCode();
        $this->assertEquals(200, $statusCode);

        $response = json_decode($responseContent, true);
        $this->assertEquals('User created', $response['result']);
        $this->assertArrayHasKey('id', $response);
    }

    public function testCreateNotUniqueUser(): void
    {
        $client = static::createClient();
        $user = $this->userGenerator->createRandomJsonUser();

        $this->sendRequest($client, $user);
        $statusCode = $client->getResponse()->getStatusCode();
        $this->assertEquals(200, $statusCode);

        $this->sendRequest($client, $user);
        $statusCode = $client->getResponse()->getStatusCode();
        $this->assertEquals(400, $statusCode);

        $responseContent = $client->getResponse()->getContent();
        $response = json_decode($responseContent, true);
        $this->assertEquals('User already exist', $response['result']);
    }

    public function testCreateUserWithEmptyFields(): void
    {
        $client = static::createClient();

        $this->sendRequest($client, $this->userGenerator->createJsonUserWithEmptyFields());
        $statusCode = $client->getResponse()->getStatusCode();
        $this->assertEquals(400, $statusCode);

        $responseContent = $client->getResponse()->getContent();
        $response = json_decode($responseContent, true);
        $this->assertEquals('Empty request parameters', $response['result']);
    }

    private function sendRequest(KernelBrowser $client, array $user): void
    {
        $client->request(
            'POST',
            '/user/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($user)
        );
    }
}