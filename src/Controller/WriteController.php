<?php


namespace App\Controller;

use App\Calendar\Api\ApiCommandInterface;
use App\Calendar\Api\Exception\UserAlreadyExistException;
use App\Calendar\Domain\Exception\MeetingOrganizerIsNotExistException;
use App\Calendar\Domain\Exception\MeetingParticipantAmountExceedsLimitException;
use App\Calendar\Domain\Exception\UserIsAlreadyMeetingParticipantException;
use App\Calendar\Domain\Exception\UserIsNotMeetingOrganizerException;
use App\Calendar\Domain\Exception\UserIsNotMeetingParticipantException;
use App\Controller\Mapper\CreateInviteRequestMapper;
use App\Controller\Mapper\CreateMeetingRequestMapper;
use App\Controller\Mapper\CreateUserRequestMapper;
use App\Controller\Mapper\DeleteUserFromMeetingRequestMapper;
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
            $id = $this->api->createMeeting($meetingInput);
            return $this->json(['result' => 'Meeting created', 'id' => $id]);
        }
        catch (MeetingOrganizerIsNotExistException $e)
        {
            return $this->json(['result' => 'Meeting organizer is not exist'], 400);
        }
    }

    public function inviteUserToMeeting(Request $request): Response
    {
        try
        {
            $inviteInput = CreateInviteRequestMapper::buildInput($request->getContent());
            $id = $this->api->createInvitation($inviteInput);
            return $this->json(['result' => 'Invitation created', 'id' => $id]);
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

    public function deleteUserFromMeeting(Request $request): Response
    {
        try
        {
            $deleteFromMeetingInput = DeleteUserFromMeetingRequestMapper::buildInput($request->getContent());
            $id = $this->api->deleteUserFromMeeting($deleteFromMeetingInput);
            return $this->json(['result' => 'User deleted from meeting', 'id' => $id]);
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
}