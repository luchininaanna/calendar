<?php


namespace App\Calendar\Infrastructure\Query;


use App\Calendar\App\Query\Data\ParticipantMeetingData;
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
            $result[] = new ParticipantMeetingData(
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
            $result[] = new ParticipantMeetingData(
                $this->uuidProvider->bytesToString($meeting['uuid']),
                $meeting['name'],
                $meeting['location'],
                $meeting['start_time']
            );
        }

        return $result;
    }
}