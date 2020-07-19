<?php


namespace App\Calendar\Infrastructure\Synchronization;


use App\Calendar\App\Synchronization\SynchronizationInterface;
use Doctrine\ORM\EntityManagerInterface;

class Synchronization implements SynchronizationInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transaction(callable $job)
    {
        $connection = $this->entityManager->getConnection();
        $connection->beginTransaction();
        try
        {
            $val = $job();
            $this->entityManager->flush();
            $connection->commit();

            return $val;
        }
        catch (\Throwable $exception)
        {
            $connection->rollBack();
            throw $exception;
        }
    }
}