<?php


namespace App\Calendar\Infrastructure\Repository;


use App\Calendar\App\Uuid\UuidProviderInterface;
use App\Calendar\Domain\Model\Meeting;
use App\Calendar\Domain\Model\MeetingRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class MeetingRepository implements MeetingRepositoryInterface
{
    private EntityManagerInterface $entityManager;
    private UuidProviderInterface $uuidProvider;

    public function __construct(EntityManagerInterface $entityManager, UuidProviderInterface $uuidProvider)
    {
        $this->entityManager = $entityManager;
        $this->uuidProvider = $uuidProvider;
    }

    public function createMeeting(Meeting $meeting): void
    {
        $dbMeeting = new \App\Entity\Meeting();
        $dbMeeting->setOrganizerUuid($this->uuidProvider->stringToBytes($meeting->getOrganizerId()));
        $dbMeeting->setName($meeting->getName());
        $dbMeeting->setLocation($meeting->getLocation());
        $dbMeeting->setStartTime($meeting->getStartTime());
        $dbMeeting->setUuid($this->uuidProvider->stringToBytes($meeting->getUuid()));

        $this->entityManager->persist($dbMeeting);
        $this->entityManager->flush();
    }
}