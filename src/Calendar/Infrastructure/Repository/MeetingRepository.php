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
        $dbMeeting->setOrganizerUuid($this->uuidProvider->stringToBytes($meeting->getLoggedUserId()));
        $dbMeeting->setName($meeting->getName());
        $dbMeeting->setLocation($meeting->getLocation());
        $dbMeeting->setStartTime($meeting->getStartTime());
        $dbMeeting->setUuid($this->uuidProvider->stringToBytes($meeting->getUuid()));

        $this->entityManager->persist($dbMeeting);
        $this->entityManager->flush();
    }

    public function isUserIsMeetingOrganizer(string $organizerUuid, string $meetingUuid): bool
    {
        $repository = $this->entityManager->getRepository('App\Entity\Meeting');
        return $repository->findOneBy(array('uuid' => $this->uuidProvider->stringToBytes($meetingUuid),
                'organizer_uuid' => $this->uuidProvider->stringToBytes($organizerUuid))) !== null;
    }

    public function isMeetingExist(string $meetingUuid): bool
    {
        $repository = $this->entityManager->getRepository('App\Entity\Meeting');
        return $repository->findOneBy(array('uuid' => $this->uuidProvider->stringToBytes($meetingUuid))) !== null;
    }

    public function deleteMeetingById(string $meetingUuid): void
    {
        $repository = $this->entityManager->getRepository('\App\Entity\Meeting');
        $record = $repository->findOneBy(array('uuid' => $this->uuidProvider->stringToBytes($meetingUuid)));

        $this->entityManager->remove($record);
        $this->entityManager->flush();
    }

    public function deleteMeetingsByUserAsOrganizer(string $organizerUuid): void
    {
        $repository = $this->entityManager->getRepository('\App\Entity\Meeting');

        $records = $repository->findBy(array('organizer_uuid' => $this->uuidProvider->stringToBytes($organizerUuid)));

        foreach ($records as $record)
        {
            $this->entityManager->remove($record);
        }

        $this->entityManager->flush();
    }
}