<?php


namespace App\Tests\Controller;


use App\Tests\Controller\JsonBuilder\MeetingJsonBuilder;
use App\Tests\Controller\JsonBuilder\UserJsonBuilder;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class EntityCreator
{
    private RequestService $requestService;
    private UserJsonBuilder $userJsonBuilder;
    private MeetingJsonBuilder $meetingJsonBuilder;

    public function __construct()
    {
        $this->requestService = new RequestService();
        $this->userJsonBuilder = new UserJsonBuilder();
        $this->meetingJsonBuilder = new MeetingJsonBuilder();
    }

    public function getUserId(KernelBrowser $client): string
    {
        $this->requestService->sendCreateUserRequest($client, $this->userJsonBuilder->createUserJson());
        $response = json_decode($client->getResponse()->getContent(), true);
        return $response['id'];
    }

    public function getMeetingId(KernelBrowser $client, string $organizerId): string
    {
        $meeting = $this->meetingJsonBuilder->createMeetingJson($organizerId);
        $this->requestService->sendCreateMeetingRequest($client, $meeting);
        $response = json_decode($client->getResponse()->getContent(), true);
        return $response['id'];
    }
}