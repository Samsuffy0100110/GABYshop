<?php

namespace App\Repository\Product;

use App\Entity\Product\FeaturedProducts;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<FeaturedProducts>
 *
 * @method FeaturedProducts|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeaturedProducts|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeaturedProducts[]    findAll()
 * @method FeaturedProducts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeaturedProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FeaturedProducts::class);
    }

    public function add(FeaturedProducts $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FeaturedProducts $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return FeaturedProducts[] Returns an array of FeaturedProducts objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FeaturedProducts
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
