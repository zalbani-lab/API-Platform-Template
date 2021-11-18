<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Animation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Animation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Animation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Animation[]    findAll()
 * @method Animation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Animation::class);
    }

    /**
     * @return Animation[] Returns an array of Animation objects
     */
    public function findAwaitingToDisplayUntilToday()
    {
        $todayDate = new \DateTime();
        // Cette requete retourne la liste des animations ayant un display a false et une displayDate inferieur ou egal a la date d'aujourd'hui

        return $this->createQueryBuilder('animation')
            ->andWhere('animation.display = :valDisplay')
            ->setParameter('valDisplay', false)
            ->andWhere('animation.displayDate <= :valDate')
            ->setParameter('valDate', $todayDate)
            ->orderBy('animation.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Animation
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
