<?php


namespace App\Controller;

use App\Calendar\Api\ApiCommandInterface;
use App\Calendar\Api\Exception\MeetingIsNotExistException;
use App\Calendar\Api\Exception\UserAlreadyExistException;
use App\Calendar\Api\Exception\MeetingOrganizerIsNotExistException;
use App\Calendar\Api\Exception\MeetingParticipantAmountExceedsLimitException;
use App\Calendar\Api\Exception\UserIsAlreadyMeetingParticipantException;
use App\Calendar\Api\Exception\UserIsNotExistException;
use App\Calendar\Api\Exception\UserIsNotMeetingOrganizerException;
use App\Calendar\Api\Exception\UserIsNotMeetingParticipantException;
use App\Controller\Mapper\CreateInviteRequestMapper;
use App\Controller\Mapper\CreateMeetingRequestMapper;
use App\Controller\Mapper\CreateUserRequestMapper;
use App\Controller\Mapper\DeleteMeetingRequestMapper;
use App\Controller\Mapper\DeleteUserFromMeetingRequestMapper;
use App\Controller\Mapper\DeleteUserRequestMapper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WriteController extends AbstractController
{
    private ApiCommandInterface $api;

    public function __construct(ApiCommandInterface $api)
    {
        $this->api = $api;
    }

    public function createUser(Request $request): Response
    {
        try
        {
            $userInput = CreateUserRequestMapper::buildInput($request->getContent());
            if ($userInput === null)
            {
                return $this->json(['result' => 'Empty request parameters'], 400);
            }

            $id = $this->api->createUser($userInput);
            return $this->json(['result' => 'User created', 'id' => $id]);
        }
        catch (UserAlreadyExistException $e)
        {
           return $this->json(['result' => 'User already exist'], 400);
        }
    }

    public function createMeeting(Request $request): Response
    {
        try
        {
            $meetingInput = CreateMeetingRequestMapper::buildInput($request->getContent());
            if ($meetingInput === null)
            {
                return $this->json(['result' => 'Empty request parameters'], 400);
            }

            $id = $this->api->createMeeting($meetingInput);
            return $this->json(['result' => 'Meeting created', 'id' => $id]);
        }
        catch (MeetingOrganizerIsNotExistException $e)
        {
            return $this->json(['result' => 'Meeting organizer is not exist'], 400);
        }
    }

    public function inviteMeetingParticipant(Request $request): Response
    {
        try
        {
            $inviteInput = CreateInviteRequestMapper::buildInput($request->getContent());
            if ($inviteInput === null)
            {
                return $this->json(['result' => 'Empty request parameters'], 400);
            }

            $this->api->inviteMeetingParticipant($inviteInput);
            return $this->json(['result' => 'Invitation created']);
        }
        catch (UserIsNotMeetingOrganizerException $e)
        {
            return $this->json(['result' => 'User is not meeting organizer'], 400);
        }
        catch (MeetingParticipantAmountExceedsLimitException $e)
        {
            return $this->json(['result' => 'Meeting participant amount exceeds limit'], 400);
        }
        catch (UserIsAlreadyMeetingParticipantException $e)
        {
            return $this->json(['result' => 'User ia already meeting participant'], 400);
        }
    }

    public function meetingParticipantDelete(Request $request): Response
    {
        try
        {
            $deleteFromMeetingInput = DeleteUserFromMeetingRequestMapper::buildInput($request->getContent());
            if ($deleteFromMeetingInput === null)
            {
                return $this->json(['result' => 'Empty request parameters'], 400);
            }

            $this->api->deleteUserFromMeeting($deleteFromMeetingInput);
            return $this->json(['result' => 'User deleted from meeting']);
        }
        catch (UserIsNotMeetingOrganizerException $e)
        {
            return $this->json(['result' => 'User is not meeting organizer'], 400);
        }
         catch (UserIsNotMeetingParticipantException $e)
        {
            return $this->json(['result' => 'User ia not meeting participant'], 400);
        }
    }

    public function deleteMeeting(Request $request): Response
    {
        try
        {
            $deleteMeetingInput = DeleteMeetingRequestMapper::buildInput($request->getContent());
            if ($deleteMeetingInput === null)
            {
                return $this->json(['result' => 'Empty request parameters'], 400);
            }

            $this->api->deleteMeeting($deleteMeetingInput);
            return $this->json(['result' => 'Meeting deleted']);
        }
        catch (MeetingIsNotExistException $e)
        {
            return $this->json(['result' => 'Meeting is not exist'], 400);
        }
        catch (UserIsNotMeetingOrganizerException $e)
        {
            return $this->json(['result' => 'User is not meeting organizer'], 400);
        }
    }

    public function deleteUser(Request $request): Response
    {
        try
        {
            $deleteUserInput = DeleteUserRequestMapper::buildInput($request->getContent());
            if ($deleteUserInput === null)
            {
                return $this->json(['result' => 'Empty request parameters'], 400);
            }

            $this->api->deleteUser($deleteUserInput);
            return $this->json(['result' => 'User deleted']);
        }
        catch (UserIsNotExistException $e)
        {
            return $this->json(['result' => 'User is not exist'], 400);
        }
    }
}