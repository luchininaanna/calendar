<?php


namespace App\Controller;

use App\Calendar\Api\ApiCommandInterface;
use App\Calendar\Api\Exception\MeetingIsNotExistException;
use App\Calendar\Api\Exception\UserAlreadyExistException;
use App\Calendar\Api\Exception\MeetingOrganizerIsNotExistException;
use App\Calendar\Api\Exception\MeetingParticipantAmountExceedsLimitException;
use App\Calendar\Api\Exception\MeetingParticipantIsAlreadyExistException;
use App\Calendar\Api\Exception\UserIsNotExistException;
use App\Calendar\Api\Exception\MeetingOrganizerIsNotCorrectException;
use App\Calendar\Api\Exception\MeetingParticipantIsNotCorrectException;
use App\Controller\InputFactory\InviteMeetingParticipantInputFactory;
use App\Controller\InputFactory\CreateMeetingInputFactory;
use App\Controller\InputFactory\CreateUserInputFactory;
use App\Controller\InputFactory\DeleteMeetingInputFactory;
use App\Controller\InputFactory\DeleteMeetingParticipantInputFactory;
use App\Controller\InputFactory\DeleteUserInputFactory;
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
            $userInput = CreateUserInputFactory::buildInput($request->getContent());
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
            $meetingInput = CreateMeetingInputFactory::buildInput($request->getContent());
            if ($meetingInput === null)
            {
                return $this->json(['result' => 'Empty request parameters'], 400);
            }

            $id = $this->api->createMeeting($meetingInput);
            return $this->json(['result' => 'Meeting created', 'id' => $id]);
        }
        catch (MeetingOrganizerIsNotExistException $e)
        {
            return $this->json(['result' => 'Meeting organizer is not exist'], 404);
        }
    }

    public function inviteMeetingParticipant(Request $request): Response
    {
        try
        {
            $inviteInput = InviteMeetingParticipantInputFactory::buildInput($request->getContent());
            if ($inviteInput === null)
            {
                return $this->json(['result' => 'Empty request parameters'], 400);
            }

            $this->api->inviteMeetingParticipant($inviteInput);
            return $this->json(['result' => 'Invitation created']);
        }
        catch (MeetingOrganizerIsNotCorrectException $e)
        {
            return $this->json(['result' => 'User is not meeting organizer'], 400);
        }
        catch (MeetingParticipantAmountExceedsLimitException $e)
        {
            return $this->json(['result' => 'Meeting participant amount exceeds limit'], 400);
        }
        catch (UserIsNotExistException $e)
        {
            return $this->json(['result' => 'User is not exist'], 404);
        }
        catch (MeetingParticipantIsAlreadyExistException $e)
        {
            return $this->json(['result' => 'User ia already meeting participant'], 400);
        }
    }

    public function deleteMeetingParticipant(Request $request): Response
    {
        try
        {
            $deleteMeetingParticipantInput = DeleteMeetingParticipantInputFactory::buildInput($request->getContent());

            if ($deleteMeetingParticipantInput === null)
            {
                return $this->json(['result' => 'Empty request parameters'], 400);
            }

            $this->api->deleteMeetingParticipant($deleteMeetingParticipantInput);
            return $this->json(['result' => 'User deleted from meeting']);
        }
        catch (MeetingOrganizerIsNotCorrectException $e)
        {
            return $this->json(['result' => 'User is not meeting organizer'], 400);
        }
         catch (MeetingParticipantIsNotCorrectException $e)
        {
            return $this->json(['result' => 'User is not meeting participant'], 400);
        }
    }

    public function deleteMeeting(Request $request): Response
    {
        try
        {
            $deleteMeetingInput = DeleteMeetingInputFactory::buildInput($request->getContent());

            if ($deleteMeetingInput === null)
            {
                return $this->json(['result' => 'Empty request parameters'], 400);
            }

            $this->api->deleteMeeting($deleteMeetingInput);
            return $this->json(['result' => 'Meeting deleted']);
        }
        catch (MeetingIsNotExistException $e)
        {
            return $this->json(['result' => 'Meeting is not exist'], 404);
        }
        catch (MeetingOrganizerIsNotCorrectException $e)
        {
            return $this->json(['result' => 'User is not meeting organizer'], 400);
        }
    }

    public function deleteUser(Request $request): Response
    {
        try
        {
            $deleteUserInput = DeleteUserInputFactory::buildInput($request->getContent());

            if ($deleteUserInput === null)
            {
                return $this->json(['result' => 'Empty request parameters'], 400);
            }

            $this->api->deleteUser($deleteUserInput);
            return $this->json(['result' => 'User deleted']);
        }
        catch (UserIsNotExistException $e)
        {
            return $this->json(['result' => 'User is not exist'], 404);
        }
    }
}