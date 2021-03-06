<?php


namespace App\Calendar\Api;

use App\Calendar\Api\Input\InviteMeetingParticipantInput;
use App\Calendar\Api\Input\CreateMeetingInput;
use App\Calendar\Api\Input\CreateUserInput;
use App\Calendar\Api\Input\DeleteMeetingInput;
use App\Calendar\Api\Input\DeleteMeetingParticipantInput;
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
use App\Calendar\App\Command\DeleteMeetingParticipantCommand;
use App\Calendar\App\Command\Handler\InviteMeetingParticipantCommandHandler;
use App\Calendar\App\Command\Handler\CreateMeetingCommandHandler;
use App\Calendar\App\Command\Handler\CreateUserCommandHandler;
use App\Calendar\App\Command\Handler\DeleteMeetingCommandHandler;
use App\Calendar\App\Command\Handler\DeleteUserCommandHandler;
use App\Calendar\App\Command\Handler\DeleteMeetingParticipantCommandHandler;
use App\Calendar\App\Query\UserQueryServiceInterface;
use App\Calendar\Domain\Exception\MeetingIsNotExistException;
use App\Calendar\Domain\Exception\MeetingOrganizerIsNotExistException;
use App\Calendar\Domain\Exception\UserAlreadyExistException;
use App\Calendar\Domain\Exception\MeetingParticipantAmountExceedsLimitException;
use App\Calendar\Domain\Exception\MeetingParticipantIsAlreadyExistException;
use App\Calendar\Domain\Exception\UserIsNotExistException;
use App\Calendar\Domain\Exception\MeetingOrganizerIsNotCorrectException;
use App\Calendar\Domain\Exception\MeetingParticipantIsNotCorrectException;

class Api implements ApiCommandInterface, ApiQueryInterface
{
    private CreateUserCommandHandler $createUserCommandHandler;
    private CreateMeetingCommandHandler $createMeetingCommandHandler;
    private InviteMeetingParticipantCommandHandler $inviteMeetingParticipantHandler;
    private DeleteMeetingParticipantCommandHandler $deleteMeetingParticipantCommandHandler;
    private DeleteMeetingCommandHandler $deleteMeetingCommandHandler;
    private DeleteUserCommandHandler $deleteUserCommandHandler;
    private UserQueryServiceInterface $userQueryService;

    public function __construct(
        CreateUserCommandHandler $createUserCommandHandler,
        CreateMeetingCommandHandler $createMeetingCommandHandler,
        InviteMeetingParticipantCommandHandler $inviteMeetingParticipantHandler,
        DeleteMeetingParticipantCommandHandler $deleteMeetingParticipantCommandHandler,
        DeleteMeetingCommandHandler $deleteMeetingCommandHandler,
        DeleteUserCommandHandler $deleteUserCommandHandler,
        UserQueryServiceInterface $userQueryService
    ) {
        $this->createUserCommandHandler = $createUserCommandHandler;
        $this->createMeetingCommandHandler = $createMeetingCommandHandler;
        $this->inviteMeetingParticipantHandler = $inviteMeetingParticipantHandler;
        $this->deleteMeetingParticipantCommandHandler = $deleteMeetingParticipantCommandHandler;
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
            $input->getInvokerId(),
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

    public function inviteMeetingParticipant(InviteMeetingParticipantInput $input): void
    {
        $command = new InviteMeetingParticipantCommand(
            $input->getInvokerId(),
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
        catch (UserIsNotExistException $e)
        {
            throw new Exception\UserIsNotExistException($e->getMessage(), $e->getCode(), $e);
        }
        catch (MeetingOrganizerIsNotCorrectException $e)
        {
            throw new Exception\MeetingOrganizerIsNotCorrectException($e->getMessage(), $e->getCode(), $e);
        }
        catch (MeetingParticipantIsAlreadyExistException $e)
        {
            throw new Exception\MeetingParticipantIsAlreadyExistException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function deleteMeetingParticipant(DeleteMeetingParticipantInput $input): void
    {
        $command = new DeleteMeetingParticipantCommand(
            $input->getInvokerId(),
            $input->getMeetingId(),
            $input->getParticipantId()
        );

        try
        {
            $this->deleteMeetingParticipantCommandHandler->handle($command);
        }
        catch (MeetingOrganizerIsNotCorrectException $e)
        {
            throw new Exception\MeetingOrganizerIsNotCorrectException($e->getMessage(), $e->getCode(), $e);
        }
        catch (MeetingParticipantIsNotCorrectException $e)
        {
            throw new Exception\MeetingParticipantIsNotCorrectException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function deleteMeeting(DeleteMeetingInput $input): void
    {
        $command = new DeleteMeetingCommand(
            $input->getInvokerId(),
            $input->getMeetingId()
        );

        try
        {
            $this->deleteMeetingCommandHandler->handle($command);
        }
        catch (MeetingOrganizerIsNotCorrectException $e)
        {
            throw new Exception\MeetingOrganizerIsNotCorrectException($e->getMessage(), $e->getCode(), $e);
        }
        catch (MeetingIsNotExistException $e)
        {
            throw new Exception\MeetingIsNotExistException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function deleteUser(DeleteUserInput $input): void
    {
        $command = new DeleteUserCommand($input->getUserId());

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

    public function getMeetingsByParticipant(string $invokerId): array
    {
        $result = [];
        foreach ($this->userQueryService->getMeetingsByParticipant($invokerId) as $meetingData)
        {
            $result[] = new MeetingOutput($meetingData);
        }

        return $result;
    }

    public function getMeetingsByOrganizer(string $invokerId): array
    {
        $result = [];
        foreach ($this->userQueryService->getMeetingsByOrganizer($invokerId) as $meetingData)
        {
            $result[] = new MeetingOutput($meetingData);
        }

        return $result;
    }

    public function getParticipantsAsOrganizer(GetParticipantInput $input): array
    {
        $result = [];
        foreach ($this->userQueryService->getParticipantsAsOrganizer($input->getInvokerId(),
            $input->getMeetingId()) as $participant)
        {
            $result[] = new ParticipantOutput($participant);
        }

        return $result;
    }
}