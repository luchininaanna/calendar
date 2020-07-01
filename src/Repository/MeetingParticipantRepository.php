<?php

namespace App\Repository;

use App\Entity\MeetingParticipant;
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

    // /**
    //  * @return MeetingParticipant[] Returns an array of MeetingParticipant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MeetingParticipant
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
