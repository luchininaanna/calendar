<?php


namespace App\Calendar\Infrastructure\Repository;

use App\Calendar\App\Uuid\UuidProviderInterface;
use App\Calendar\Domain\Model\MeetingParticipantRepositoryInterface;
use App\Calendar\Domain\Model\MeetingParticipant;
use Doctrine\ORM\EntityManagerInterface;

class MeetingParticipantRepository implements MeetingParticipantRepositoryInterface
{
    private const PARTICIPANT_LIMIT = 10;

    private UuidProviderInterface $uuidProvider;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, UuidProviderInterface $uuidProvider)
    {
        $this->uuidProvider = $uuidProvider;
        $this->entityManager = $entityManager;
    }

    public function createMeetingParticipant(MeetingParticipant $meetingParticipant): void
    {
        $dbMeetingParticipant = new \App\Entity\MeetingParticipant();
        $dbMeetingParticipant->setUserUuid($this->uuidProvider->stringToBytes($meetingParticipant->getParticipantId()));
        $dbMeetingParticipant->setMeetingUuid($this->uuidProvider->stringToBytes($meetingParticipant->getMeetingId()));

        $this->entityManager->persist($dbMeetingParticipant);
        $this->entityManager->flush();
    }

    public function isMeetingHasNotAcceptableNumberOfParticipants(string $meetingUuid): bool
    {
        $repository = $this->entityManager->getRepository(\App\Entity\MeetingParticipant::class);
        $participantList = $repository->findBy(array('meeting_uuid' => $this->uuidProvider->stringToBytes($meetingUuid)));
        $participantCount = count($participantList);
        return ($participantCount >= MeetingParticipantRepository::PARTICIPANT_LIMIT);
    }

    public function isMeetingParticipant(string $userUuid, string $meetingUuid): bool
    {
        $repository = $this->entityManager->getRepository(\App\Entity\MeetingParticipant::class);
        return $repository->findOneBy(array('user_uuid' => $this->uuidProvider->stringToBytes($userUuid),
                'meeting_uuid' => $this->uuidProvider->stringToBytes($meetingUuid))) !== null;
    }

    public function deleteMeetingParticipant(string $userUuid, string $meetingUuid): void
    {
        $repository = $this->entityManager->getRepository(\App\Entity\MeetingParticipant::class);
        $record = $repository->findOneBy(array('user_uuid' => $this->uuidProvider->stringToBytes($userUuid),
            'meeting_uuid' => $this->uuidProvider->stringToBytes($meetingUuid)));

        $this->entityManager->remove($record);
        $this->entityManager->flush();
    }

    public function deleteParticipantFromAllMeetings(string $userUuid): void
    {
        $repository = $this->entityManager->getRepository(\App\Entity\MeetingParticipant::class);
        $records = $repository->findBy(array('user_uuid' => $this->uuidProvider->stringToBytes($userUuid)));

        foreach ($records as $record)
        {
            $this->entityManager->remove($record);
        }

        $this->entityManager->flush();
    }

    public function deleteAllMeetingParticipants(string $meetingUuid): void
    {
        $repository = $this->entityManager->getRepository(\App\Entity\MeetingParticipant::class);

        $records = $repository->findBy(array('meeting_uuid' => $this->uuidProvider->stringToBytes($meetingUuid)));

        foreach ($records as $record)
        {
            $this->entityManager->remove($record);
        }

        $this->entityManager->flush();
    }
}