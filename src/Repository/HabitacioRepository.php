<?php

namespace App\Repository;

use App\Entity\Habitacio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Habitacio>
 */
class HabitacioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Habitacio::class);
    }


    public function remove(Habitacio $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * @return Habitacio[] Returns an array of Imagen objects
     */
    public function findLikeDescripcion(string $value): array
    {
        $qb = $this->createQueryBuilder('i');
        $qb->Where($qb->expr()->like('i.nombre', ':val'))->setParameter('val', '%' . $value . '%');
        return $qb->getQuery()->getResult();
    }



    public function findByLocationAndCapacity(string $localitzacio, int $capacitat): array
    {
        // Crear la consulta para filtrar por localización y capacidad
        return $this->createQueryBuilder('h')
            ->andWhere('h.localitzacio = :localitzacio')
            ->andWhere('h.capacitat >= :capacitat')
            ->setParameter('localitzacio', $localitzacio)
            ->setParameter('capacitat', $capacitat)
            ->getQuery()
            ->getResult();
    }


    //    /**
    //     * @return Habitacio[] Returns an array of Habitacio objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('h.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Habitacio
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
