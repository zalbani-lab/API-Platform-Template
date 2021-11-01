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
    public function findAwaitingToPublishUntilToday()
    {
        $animationStatusName = 'EN ATTENTE DE PUBLICATION';
        $todayDate = new \DateTime();
        // Cette requete retourne la liste des animations ayant pour status : En attente de publications
        // et qui ont une date de publication inferieur ou egal a la date d'aujourd'hui

        // TO-DO : Ajouter la vrai column en base de donnee correspondant a la date de publication voulu
        return $this->createQueryBuilder('animation')
            ->leftJoin('animation.status', 'animation_status')
            ->andWhere('animation.publicationDate <= :valDate')
            ->setParameter('valDate', $todayDate)
            ->andWhere('animation_status.name = :valStatus')
            ->setParameter('valStatus', $animationStatusName)
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
