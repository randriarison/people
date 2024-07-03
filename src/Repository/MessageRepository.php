<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    //    /**
    //     * @return Message[] Returns an array of Message objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Message
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findConversationMessages(int $conversationId): array
    {
        $qb = $this->createQueryBuilder('m');
        $res = $qb
            ->join('m.author', 'u')
            ->join('m.conversation', 'c')
            ->select(['m.content', 'm.id as mid', 'm.creationDate as date', 'u.firstname', 'u.lastname', 'u.avatar', 'u.id as uid', 'c.id as cid', ])
            ->where('c.id = :conversationId')
            ->setParameter('conversationId', $conversationId)
            ->orderBy('m.creationDate', 'DESC')
            ->setMaxResults(15)
            ->getQuery()
            ->getResult();
        return array_reverse($res);
    }
}
