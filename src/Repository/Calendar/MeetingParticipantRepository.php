<?php

namespace App\Repository\Calendar;

use App\Entity\Calendar\MeetingParticipant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MeetingParticipant|null find($id, $lockMode = null, $lockVersion = null)
 * @method MeetingParticipant|null findOneBy(array $criteria, array $orderBy = null)
 * @method MeetingParticipant[]    findAll()
 * @method MeetingParticipant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeetingParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MeetingParticipant::class);
    }
}
