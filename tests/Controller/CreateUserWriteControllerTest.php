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
        $statusCode = $this->sendRequest($client, $this->userGenerator->createRandomJsonUser());
        $this->assertEquals(200, $statusCode);
    }

    public function testCreateNotUniqueUser(): void
    {
        $client = static::createClient();
        $user = $this->userGenerator->createRandomJsonUser();
        $statusCode = $this->sendRequest($client, $user);
        $this->assertEquals(200, $statusCode);
        $statusCode = $this->sendRequest($client, $user);
        $this->assertEquals(400, $statusCode);
    }

    public function testCreateUserWithEmptyFields(): void
    {
        $client = static::createClient();
        $statusCode = $this->sendRequest($client, $this->userGenerator->createJsonUserWithEmptyFields());
        $this->assertEquals(400, $statusCode);
    }

    private function sendRequest(KernelBrowser $client, array $user): int
    {
        $client->request(
            'POST',
            '/user/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($user)
        );
        return $client->getResponse()->getStatusCode();
    }
}