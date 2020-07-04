<?php


namespace App\Calendar\Infrastructure\Repository;

use App\Calendar\App\Uuid\UuidProviderInterface;
use App\Calendar\Domain\Model\MeetingParticipantRepositoryInterface;
use App\Calendar\Domain\Model\MeetingParticipant;
use Doctrine\ORM\EntityManagerInterface;

class MeetingParticipantRepository implements MeetingParticipantRepositoryInterface
{
    private const PARTICIPANT_LIMIT = 10;

    private EntityManagerInterface $entityManager;
    private UuidProviderInterface $uuidProvider;

    public function __construct(EntityManagerInterface $entityManager, UuidProviderInterface $uuidProvider)
    {
        $this->entityManager = $entityManager;
        $this->uuidProvider = $uuidProvider;
    }

    public function createMeetingParticipant(MeetingParticipant $meetingParticipant): void
    {
        $dbMeetingParticipant = new \App\Entity\MeetingParticipant();
        $dbMeetingParticipant->setUserUuid($this->uuidProvider->stringToBytes($meetingParticipant->getUserUuid()));
        $dbMeetingParticipant->setMeetingUuid($this->uuidProvider->stringToBytes($meetingParticipant->getMeetingUuid()));

        $this->entityManager->persist($dbMeetingParticipant);
        $this->entityManager->flush();
    }

    public function isMeetingHasNotAcceptableNumberOfParticipants(string $meetingUuid): bool
    {
        $repository = $this->entityManager->getRepository('App\Entity\MeetingParticipant');
        $participantList = $repository->findBy(array('meeting_uuid' => $this->uuidProvider->stringToBytes($meetingUuid)));
        $participantCount = count($participantList);
        return ($participantCount >= MeetingParticipantRepository::PARTICIPANT_LIMIT);
    }

    public function isUserIsMeetingParticipant(string $userUuid, string $meetingUuid): bool
    {
        $repository = $this->entityManager->getRepository('App\Entity\MeetingParticipant');
        return $repository->findOneBy(array('user_uuid' => $this->uuidProvider->stringToBytes($userUuid),
                'meeting_uuid' => $this->uuidProvider->stringToBytes($meetingUuid))) !== null;
    }

    public function deleteUserFromParticipant(string $userUuid, string $meetingUuid): void
    {
        $repository = $this->entityManager->getRepository('\App\Entity\MeetingParticipant');
        $record = $repository->findOneBy(array('user_uuid' => $this->uuidProvider->stringToBytes($userUuid),
            'meeting_uuid' => $this->uuidProvider->stringToBytes($meetingUuid)));

        $this->entityManager->remove($record);
        $this->entityManager->flush();
    }
}