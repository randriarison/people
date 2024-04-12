<?php

namespace App\Repository;

use App\Entity\PersonSkills;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PersonSkills>
 *
 * @method PersonSkills|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonSkills|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonSkills[]    findAll()
 * @method PersonSkills[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonSkillsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonSkills::class);
    }

//    /**
//     * @return PersonSkills[] Returns an array of PersonSkills objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PersonSkills
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
