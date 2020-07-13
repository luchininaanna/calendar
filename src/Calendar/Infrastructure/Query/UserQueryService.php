<?php


namespace App\Calendar\Infrastructure\Query;


use App\Calendar\App\Query\Data\ParticipantData;
use App\Calendar\App\Query\Data\MeetingData;
use App\Calendar\App\Query\Data\UserData;
use App\Calendar\App\Uuid\UuidProviderInterface;
use App\Calendar\Domain\Model\User;
use Doctrine\DBAL\Driver\Connection;

class UserQueryService implements \App\Calendar\App\Query\UserQueryServiceInterface
{
    private Connection $connection;
    private UuidProviderInterface $uuidProvider;

    public function __construct(Connection $connection, UuidProviderInterface $uuidProvider)
    {
        $this->connection = $connection;
        $this->uuidProvider = $uuidProvider;
    }

    /**
     * @inheritDoc
     */
    public function getAllUsers(): array
    {
        $users = $this->connection->fetchAll("SELECT * FROM user");

        $result = [];
        foreach ($users as $user)
        {
            $result[] = new UserData(
                $this->uuidProvider->bytesToString($user['uuid']),
                $user['login'],
                $user['name'],
                $user['surname'],
                $user['patronymic']
            );
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getMeetingsWithParticipant(string $loggedUserId): array
    {
        $meetings = $this->connection->fetchAll("
            SELECT
                m.*
            FROM
                meeting m
                INNER JOIN meeting_participant mp ON (m.uuid = mp.meeting_uuid)
            WHERE
                mp.user_uuid = :user_id
        ", ['user_id' => $this->uuidProvider->stringToBytes($loggedUserId)]);

        $result = [];
        foreach ($meetings as $meeting)
        {
            $result[] = new MeetingData(
                $this->uuidProvider->bytesToString($meeting['uuid']),
                $meeting['name'],
                $meeting['location'],
                $meeting['start_time']
            );
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getMeetingsWithOrganizer(string $loggedUserId): array
    {
        $meetings = $this->connection->fetchAll("
            SELECT
                m.*
            FROM
                meeting m
            WHERE
                m.organizer_uuid = :user_id
        ", ['user_id' => $this->uuidProvider->stringToBytes($loggedUserId)]);

        $result = [];
        foreach ($meetings as $meeting)
        {
            $result[] = new MeetingData(
                $this->uuidProvider->bytesToString($meeting['uuid']),
                $meeting['name'],
                $meeting['location'],
                $meeting['start_time']
            );
        }

        return $result;
    }

    public function getParticipantsWithOrganizer(string $loggedUserId, string $meetingId): array
    {
        $participants = $this->connection->fetchAll("
            SELECT
                m.*, u.*
            FROM
                meeting m
                INNER JOIN meeting_participant mp ON (m.uuid = mp.meeting_uuid)
                INNER JOIN user u ON (mp.user_uuid = u.uuid)
            WHERE
                m.organizer_uuid = :user_id 
                AND
                m.uuid = :meeting_id
            ORDER BY m.start_time ASC
        ", ['user_id' => $this->uuidProvider->stringToBytes($loggedUserId),
            'meeting_id' => $this->uuidProvider->stringToBytes($meetingId)]);

        $result = [];
        foreach ($participants as $participant)
        {
            $result[] = new ParticipantData(
                $this->uuidProvider->bytesToString($participant['uuid']),
                $participant['login'],
                $participant['name'],
                $participant['surname'],
                $participant['patronymic'],
                $participant['start_time']
            );
        }

        return $result;
    }
}