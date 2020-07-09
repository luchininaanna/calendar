<?php


namespace App\Tests\Controller;


use App\Tests\Controller\Generators\UserGenerator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeleteUserWriteControllerTest extends WebTestCase
{
    private UserGenerator $userGenerator;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->userGenerator = new UserGenerator();
    }

    public function testDeleteUser(): void
    {
        $client = static::createClient();
        $this->sendCreateUserRequest($client, $this->userGenerator->createRandomJsonUser());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->sendDeleteUserRequest($client, $response['id']);

        $statusCode = $client->getResponse()->getStatusCode();
        $this->assertEquals(200, $statusCode);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('User deleted', $response['result']);
    }

    public function testDeleteNotExistUser(): void
    {
        $client = static::createClient();
        $this->sendDeleteUserRequest($client, "0da175e1-11fd-4bed-b3f1-40deaffb43c1");

        $statusCode = $client->getResponse()->getStatusCode();
        $this->assertEquals(400, $statusCode);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('User is not exist', $response['result']);
    }

    private function sendCreateUserRequest(KernelBrowser $client, array $user): void
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

    private function sendDeleteUserRequest(KernelBrowser $client, string $userId): void
    {
        $client->request(
            'POST',
            '/user/delete',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                "userId" => $userId,
            ])
        );
    }
}