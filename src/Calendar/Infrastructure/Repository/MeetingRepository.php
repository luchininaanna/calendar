<?php


namespace App\Calendar\Infrastructure\Repository;

use App\Calendar\App\Uuid\UuidProviderInterface;
use App\Calendar\Domain\Model\Meeting;
use App\Calendar\Domain\Model\MeetingRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class MeetingRepository implements MeetingRepositoryInterface
{
    private UuidProviderInterface $uuidProvider;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, UuidProviderInterface $uuidProvider)
    {
        $this->uuidProvider = $uuidProvider;
        $this->entityManager = $entityManager;
    }

    public function createMeeting(Meeting $meeting): void
    {
        $dbMeeting = new \App\Entity\Meeting();
        $dbMeeting->setName($meeting->getName());
        $dbMeeting->setLocation($meeting->getLocation());
        $dbMeeting->setStartTime($meeting->getStartTime());
        $dbMeeting->setUuid($this->uuidProvider->stringToBytes($meeting->getId()));
        $dbMeeting->setOrganizerUuid($this->uuidProvider->stringToBytes($meeting->getOrganizerId()));

        $this->entityManager->persist($dbMeeting);
        $this->entityManager->flush();
    }

    public function isMeetingOrganizer(string $organizerId, string $meetingId): bool
    {
        $repository = $this->entityManager->getRepository(\App\Entity\Meeting::class);
        return $repository->findOneBy(array('uuid' => $this->uuidProvider->stringToBytes($meetingId),
                'organizer_uuid' => $this->uuidProvider->stringToBytes($organizerId))) !== null;
    }

    public function isMeetingExist(string $meetingId): bool
    {
        $repository = $this->entityManager->getRepository(\App\Entity\Meeting::class);
        return $repository->findOneBy(array('uuid' => $this->uuidProvider->stringToBytes($meetingId))) !== null;
    }

    public function deleteMeetingById(string $meetingId): void
    {
        $repository = $this->entityManager->getRepository(\App\Entity\Meeting::class);
        $record = $repository->findOneBy(array('uuid' => $this->uuidProvider->stringToBytes($meetingId)));

        $this->entityManager->remove($record);
        $this->entityManager->flush();
    }

    public function deleteMeetingsByOrganizer(string $organizerId): void
    {
        $repository = $this->entityManager->getRepository(\App\Entity\Meeting::class);
        $records = $repository->findBy(array('organizer_uuid' => $this->uuidProvider->stringToBytes($organizerId)));

        foreach ($records as $record)
        {
            $this->entityManager->remove($record);
        }

        $this->entityManager->flush();
    }
}