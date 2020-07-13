<?php


namespace App\Controller;


use App\Calendar\Api\ApiQueryInterface;
use App\Controller\Mapper\GetParticipantsRequestMapper;
use App\Controller\Mapper\GetMeetingsRequestMapper;
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
        $loggedUserId = GetMeetingsRequestMapper::buildInput($request->getContent());
        if ($loggedUserId === null)
        {
            return $this->json(['result' => 'Empty request parameters'], 400);
        }

        $json = [];
        foreach ($this->api->getMeetingsByParticipant($loggedUserId) as $meeting)
        {
            $json[] = $meeting->asAssoc();
        }
        return $this->json($json);
    }

    public function getMeetingsByOrganizer(Request $request): Response
    {
        $loggedUserId = GetMeetingsRequestMapper::buildInput($request->getContent());
        if ($loggedUserId === null)
        {
            return $this->json(['result' => 'Empty request parameters'], 400);
        }

        $json = [];
        foreach ($this->api->getMeetingsByOrganizer($loggedUserId) as $meeting)
        {
            $json[] = $meeting->asAssoc();
        }
        return $this->json($json);
    }

    public function getParticipantsAsOrganizer(Request $request): Response
    {
        $getParticipantInput = GetParticipantsRequestMapper::buildInput($request->getContent());
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