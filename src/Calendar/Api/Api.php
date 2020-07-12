<?php


namespace App\Calendar\Api;

use App\Calendar\Api\Input\CreateInvitationInput;
use App\Calendar\Api\Input\CreateMeetingInput;
use App\Calendar\Api\Input\CreateUserInput;
use App\Calendar\Api\Input\DeleteMeetingInput;
use App\Calendar\Api\Input\DeleteUserFromMeetingInput;
use App\Calendar\Api\Input\DeleteUserInput;
use App\Calendar\Api\Input\GetParticipantInput;
use App\Calendar\Api\Output\ParticipantOutput;
use App\Calendar\Api\Output\MeetingOutput;
use App\Calendar\Api\Output\UserOutput;
use App\Calendar\App\Command\InviteMeetingParticipantCommand;
use App\Calendar\App\Command\CreateMeetingCommand;
use App\Calendar\App\Command\CreateUserCommand;
use App\Calendar\App\Command\DeleteMeetingCommand;
use App\Calendar\App\Command\DeleteUserCommand;
use App\Calendar\App\Command\DeleteUserFromMeetingCommand;
use App\Calendar\App\Command\Handler\InviteMeetingParticipantCommandHandler;
use App\Calendar\App\Command\Handler\CreateMeetingCommandHandler;
use App\Calendar\App\Command\Handler\CreateUserCommandHandler;
use App\Calendar\App\Command\Handler\DeleteMeetingCommandHandler;
use App\Calendar\App\Command\Handler\DeleteUserCommandHandler;
use App\Calendar\App\Command\Handler\DeleteUserFromMeetingCommandHandler;
use App\Calendar\App\Query\UserQueryServiceInterface;
use App\Calendar\Domain\Exception\MeetingIsNotExistException;
use App\Calendar\Domain\Exception\MeetingOrganizerIsNotExistException;
use App\Calendar\Domain\Exception\UserAlreadyExistException;
use App\Calendar\Domain\Exception\MeetingParticipantAmountExceedsLimitException;
use App\Calendar\Domain\Exception\UserIsAlreadyMeetingParticipantException;
use App\Calendar\Domain\Exception\UserIsNotExistException;
use App\Calendar\Domain\Exception\UserIsNotMeetingOrganizerException;
use App\Calendar\Domain\Exception\UserIsNotMeetingParticipantException;

class Api implements ApiCommandInterface, ApiQueryInterface
{
    private CreateUserCommandHandler $createUserCommandHandler;
    private CreateMeetingCommandHandler $createMeetingCommandHandler;
    private InviteMeetingParticipantCommandHandler $inviteMeetingParticipantHandler;
    private DeleteUserFromMeetingCommandHandler $deleteUserFromMeetingCommandHandler;
    private DeleteMeetingCommandHandler $deleteMeetingCommandHandler;
    private DeleteUserCommandHandler $deleteUserCommandHandler;
    private UserQueryServiceInterface $userQueryService;

    public function __construct(
        CreateUserCommandHandler $createUserCommandHandler,
        CreateMeetingCommandHandler $createMeetingCommandHandler,
        InviteMeetingParticipantCommandHandler $inviteMeetingParticipantHandler,
        DeleteUserFromMeetingCommandHandler $deleteUserFromMeetingCommandHandler,
        DeleteMeetingCommandHandler $deleteMeetingCommandHandler,
        DeleteUserCommandHandler $deleteUserCommandHandler,
        UserQueryServiceInterface $userQueryService
    ) {
        $this->createUserCommandHandler = $createUserCommandHandler;
        $this->createMeetingCommandHandler = $createMeetingCommandHandler;
        $this->inviteMeetingParticipantHandler = $inviteMeetingParticipantHandler;
        $this->deleteUserFromMeetingCommandHandler = $deleteUserFromMeetingCommandHandler;
        $this->deleteMeetingCommandHandler = $deleteMeetingCommandHandler;
        $this->deleteUserCommandHandler = $deleteUserCommandHandler;
        $this->userQueryService = $userQueryService;
    }

    public function createUser(CreateUserInput $input): string
    {
        $command = new CreateUserCommand(
            $input->getLogin(),
            $input->getSurname(),
            $input->getName(),
            $input->getPatronymic()
        );

        try
        {
            return $this->createUserCommandHandler->handle($command);
        }
        catch (UserAlreadyExistException $e)
        {
            throw new Exception\UserAlreadyExistException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function createMeeting(CreateMeetingInput $input): string
    {
        $command = new CreateMeetingCommand(
            $input->getLoggedUserId(),
            $input->getName(),
            $input->getLocation(),
            $input->getStartTime()
        );

        try
        {
            return $this->createMeetingCommandHandler->handle($command);
        }
        catch (MeetingOrganizerIsNotExistException $e)
        {
            throw new Exception\MeetingOrganizerIsNotExistException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function inviteMeetingParticipant(CreateInvitationInput $input): void
    {
        $command = new InviteMeetingParticipantCommand(
            $input->getLoggedUserId(),
            $input->getMeetingId(),
            $input->getParticipantId()
        );

        try
        {
            $this->inviteMeetingParticipantHandler->handle($command);
        }
        catch (MeetingParticipantAmountExceedsLimitException $e)
        {
            throw new Exception\MeetingParticipantAmountExceedsLimitException($e->getMessage(), $e->getCode(), $e);
        }
        catch (UserIsNotMeetingOrganizerException $e)
        {
            throw new Exception\UserIsNotMeetingOrganizerException($e->getMessage(), $e->getCode(), $e);
        }
        catch (UserIsAlreadyMeetingParticipantException $e)
        {
            throw new Exception\UserIsAlreadyMeetingParticipantException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function deleteUserFromMeeting(DeleteUserFromMeetingInput $input): void
    {
        $command = new DeleteUserFromMeetingCommand(
            $input->getLoggedUserId(),
            $input->getMeetingId(),
            $input->getParticipantId()
        );

        try
        {
            $this->deleteUserFromMeetingCommandHandler->handle($command);
        }
        catch (UserIsNotMeetingOrganizerException $e)
        {
            throw new Exception\UserIsNotMeetingOrganizerException($e->getMessage(), $e->getCode(), $e);
        }
        catch (UserIsNotMeetingParticipantException $e)
        {
            throw new Exception\UserIsNotMeetingParticipantException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function deleteMeeting(DeleteMeetingInput $input): void
    {
        $command = new DeleteMeetingCommand(
            $input->getLoggedUserId(),
            $input->getMeetingId()
        );

        try
        {
            $this->deleteMeetingCommandHandler->handle($command);
        }
        catch (UserIsNotMeetingOrganizerException $e)
        {
            throw new Exception\UserIsNotMeetingOrganizerException($e->getMessage(), $e->getCode(), $e);
        }
        catch ( MeetingIsNotExistException$e)
        {
            throw new Exception\MeetingIsNotExistException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function deleteUser(DeleteUserInput $input): void
    {
        $command = new DeleteUserCommand(
            $input->getUserId()
        );

        try
        {
            $this->deleteUserCommandHandler->handle($command);
        }
        catch (UserIsNotExistException $e)
        {
            throw new Exception\UserIsNotExistException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getAllUsers(): array
    {
        $result = [];
        foreach ($this->userQueryService->getAllUsers() as $userData)
        {
            $result[] = new UserOutput($userData);
        }

        return $result;
    }

    public function getMeetingsWithParticipant(string $loggedUserId): array
    {
        $result = [];
        foreach ($this->userQueryService->getMeetingsWithParticipant($loggedUserId) as $meetingData)
        {
            $result[] = new MeetingOutput($meetingData);
        }

        return $result;
    }

    public function getMeetingsWithOrganizer(string $loggedUserId): array
    {
        $result = [];
        foreach ($this->userQueryService->getMeetingsWithOrganizer($loggedUserId) as $meetingData)
        {
            $result[] = new MeetingOutput($meetingData);
        }

        return $result;
    }

    public function getParticipantsWithOrganizer(GetParticipantInput $input): array
    {
        $result = [];
        foreach ($this->userQueryService->getParticipantsWithOrganizer($input->getLoggedUserId(),
            $input->getMeetingId()) as $participant)
        {
            $result[] = new ParticipantOutput($participant);
        }

        return $result;
    }
}