<?php

namespace App\Repository;

use App\Entity\Conversation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Conversation>
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    //    /**
    //     * @return Conversation[] Returns an array of Conversation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Conversation
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findByUser(User $user) : array
    {
        $qb = $this->createQueryBuilder('c');
        return $qb->where($qb->expr()->isMemberOf(':user', 'c.users'))
            ->setParameter('user', $user)
            ->orderBy('c.updateDate' , 'DESC')
            ->addOrderBy('c.creationDate' ,'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findWithLastMessageByUser(UserInterface $user) : array
    {
        $qb = $this->createQueryBuilder('c');
        return $qb
            ->select(['c.id as cid', 'm1.id as message_id', 'm1.content as content', 'm1.creationDate as date'])
            ->leftJoin('c.messages', 'm1')
            ->leftJoin(
                'c.messages',
                'm2',
                'WITH',
                $qb->expr()->orX('m1.creationDate < m2.creationDate', $qb->expr()->andX('m1.creationDate = m2.creationDate',  'm1.id < m2.id'))
            )
            ->where($qb->expr()->isMemberOf(':user', 'c.users'))
            ->andWhere($qb->expr()->isNull('m2.id'))
            ->setParameter('user', $user)
            ->orderBy('c.updateDate' , 'DESC')
            ->addOrderBy('c.creationDate' ,'DESC')
            ->getQuery()
            ->getResult();
    }
}
