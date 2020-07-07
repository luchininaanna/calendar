<?php


namespace App\Calendar\Infrastructure\Query;


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

        $response = [];
        foreach ($users as $user)
        {
            $response[] = new UserData(
                $this->uuidProvider->bytesToString($user['uuid']),
                $user['login'],
                $user['name'],
                $user['surname'],
                $user['patronymic']
            );
        }

        return $response;
    }
}