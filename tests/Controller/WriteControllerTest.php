<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WriteControllerTest extends WebTestCase
{
    public function testAddUser(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/user/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                "login" => "micci1Disnay",
                "name" => "Racc",
                "surname" => "Oon",
                "patronymic" => "RO",
            ])
        );

        $this->assertResponseIsSuccessful();
    }
}