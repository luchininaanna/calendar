<?php


namespace App\Calendar\Infrastructure\Repository;

use App\Calendar\App\Uuid\UuidProviderInterface;
use App\Calendar\Domain\Model\MeetingParticipantRepositoryInterface;
use App\Calendar\Domain\Model\MeetingParticipant;
use Doctrine\ORM\EntityManagerInterface;

class MeetingParticipantRepository implements MeetingParticipantRepositoryInterface
{
    private UuidProviderInterface $uuidProvider;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, UuidProviderInterface $uuidProvider)
    {
        $this->uuidProvider = $uuidProvider;
        $this->entityManager = $entityManager;
    }

    public function createMeetingParticipant(MeetingParticipant $meetingParticipant): void
    {
        $dbMeetingParticipant = new \App\Entity\Calendar\MeetingParticipant();
        $dbMeetingParticipant->setUserUuid($this->uuidProvider->stringToBytes($meetingParticipant->getParticipantId()));
        $dbMeetingParticipant->setMeetingUuid($this->uuidProvider->stringToBytes($meetingParticipant->getMeetingId()));

        $this->entityManager->persist($dbMeetingParticipant);
        $this->entityManager->flush();
    }

    public function getMeetingParticipantAmount(string $meetingId): int
    {
        $repository = $this->entityManager->getRepository(\App\Entity\Calendar\MeetingParticipant::class);
        $participantList = $repository->findBy(['meeting_uuid' => $this->uuidProvider->stringToBytes($meetingId)]);
        return count($participantList);
    }

    public function isMeetingParticipant(string $userId, string $meetingId): bool
    {
        $repository = $this->entityManager->getRepository(\App\Entity\Calendar\MeetingParticipant::class);
        return $repository->findOneBy(['user_uuid' => $this->uuidProvider->stringToBytes($userId),
                'meeting_uuid' => $this->uuidProvider->stringToBytes($meetingId)]) !== null;
    }

    public function deleteMeetingParticipant(string $userId, string $meetingId): void
    {
        $repository = $this->entityManager->getRepository(\App\Entity\Calendar\MeetingParticipant::class);
        $record = $repository->findOneBy([
            'user_uuid' => $this->uuidProvider->stringToBytes($userId),
            'meeting_uuid' => $this->uuidProvider->stringToBytes($meetingId),
        ]);

        $this->entityManager->remove($record);
        $this->entityManager->flush();
    }

    public function deleteParticipantFromAllMeetings(string $userId): void
    {
        $repository = $this->entityManager->getRepository(\App\Entity\Calendar\MeetingParticipant::class);
        $records = $repository->findBy(['user_uuid' => $this->uuidProvider->stringToBytes($userId)]);

        foreach ($records as $record)
        {
            $this->entityManager->remove($record);
        }

        $this->entityManager->flush();
    }

    public function deleteAllMeetingParticipants(string $meetingId): void
    {
        $repository = $this->entityManager->getRepository(\App\Entity\Calendar\MeetingParticipant::class);

        $records = $repository->findBy(['meeting_uuid' => $this->uuidProvider->stringToBytes($meetingId)]);

        foreach ($records as $record)
        {
            $this->entityManager->remove($record);
        }

        $this->entityManager->flush();
    }
}