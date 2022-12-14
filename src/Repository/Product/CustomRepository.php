<?php

namespace App\Repository\Product;

use App\Entity\Product\Custom;
use App\Entity\Product\Attribut;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Custom>
 *
 * @method Custom|null find($id, $lockMode = null, $lockVersion = null)
 * @method Custom|null findOneBy(array $criteria, array $orderBy = null)
 * @method Custom[]    findAll()
 * @method Custom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Custom::class);
    }

    public function save(Custom $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Custom $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneDescriptionByAttribut(Attribut $attribut): ?Custom
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.attribut = :attribut')
            ->setParameter('attribut', $attribut)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

//    /**
//     * @return Custom[] Returns an array of Custom objects
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

//    public function findOneBySomeField($value): ?Custom
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
