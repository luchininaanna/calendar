<?php


namespace App\Controller;


use App\Calendar\Api\ApiQueryInterface;
use App\Controller\Mapper\GetMeetingsRequestMapper;
use App\Controller\Mapper\GetMeetingsWithParticipantRequestMapper;
use Symfony\Component\HttpFoundation\RequecreateUserst;
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

    public function getAllUsers(Request $request): Response
    {
        $json = [];
        foreach ($this->api->getAllUsers() as $user)
        {
            $json[] = $user->asAssoc();
        }
        return $this->json($json);
    }

    public function getMeetingsWithParticipant(Request $request): Response
    {
        $loggedUserId = GetMeetingsRequestMapper::buildInput($request->getContent());
        if ($loggedUserId === null)
        {
            return $this->json(['result' => 'Empty request parameters'], 400);
        }

        $json = [];
        foreach ($this->api->getMeetingsWithParticipant($loggedUserId) as $meeting)
        {
            $json[] = $meeting->asAssoc();
        }
        return $this->json($json);
    }

    public function getMeetingsWithOrganizer(Request $request): Response
    {
        $loggedUserId = GetMeetingsRequestMapper::buildInput($request->getContent());
        if ($loggedUserId === null)
        {
            return $this->json(['result' => 'Empty request parameters'], 400);
        }

        $json = [];
        foreach ($this->api->getMeetingsWithOrganizer($loggedUserId) as $meeting)
        {
            $json[] = $meeting->asAssoc();
        }
        return $this->json($json);
    }
}