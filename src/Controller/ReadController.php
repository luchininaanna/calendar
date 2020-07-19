<?php


namespace App\Controller;


use App\Calendar\Api\ApiQueryInterface;
use App\Controller\InputFactory\GetParticipantsInputFactory;
use App\Controller\InputFactory\GetMeetingsInputFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReadController extends AbstractController
{
    private ApiQueryInterface $api;

    public function __construct(ApiQueryInterface $api)
    {
        $this->api = $api;
    }

    public function getAllUsers(): Response
    {
        $json = [];
        foreach ($this->api->getAllUsers() as $user)
        {
            $json[] = $user->asAssoc();
        }

        return $this->json($json);
    }

    public function getMeetingsByParticipant(Request $request): Response
    {
        $invokerId = GetMeetingsInputFactory::buildInput($request->getContent());
        if ($invokerId === null)
        {
            return $this->json(['result' => 'Empty request parameters'], 400);
        }

        $json = [];
        foreach ($this->api->getMeetingsByParticipant($invokerId) as $meeting)
        {
            $json[] = $meeting->asAssoc();
        }
        return $this->json($json);
    }

    public function getMeetingsByOrganizer(Request $request): Response
    {
        $invokerId = GetMeetingsInputFactory::buildInput($request->getContent());
        if ($invokerId === null)
        {
            return $this->json(['result' => 'Empty request parameters'], 400);
        }

        $json = [];
        foreach ($this->api->getMeetingsByOrganizer($invokerId) as $meeting)
        {
            $json[] = $meeting->asAssoc();
        }
        return $this->json($json);
    }

    public function getParticipantsAsOrganizer(Request $request): Response
    {
        $getParticipantInput = GetParticipantsInputFactory::buildInput($request->getContent());
        if ($getParticipantInput === null)
        {
            return $this->json(['result' => 'Empty request parameters'], 400);
        }

        $json = [];
        foreach ($this->api->getParticipantsAsOrganizer($getParticipantInput) as $participant)
        {
            $json[] = $participant->asAssoc();
        }
        return $this->json($json);
    }
}