<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findByState($state)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.status = :status')
            ->setParameter('status', $state)
            ->getQuery()
            ->getResult();
    }
    public function findActive($user)
    {
        $exludedStates = ['En création','Historisée'];
        return $this->createQueryBuilder('e')
            ->leftJoin('e.organiser', 'u')
            ->andWhere('e.status != :creationStatus OR (e.status = :creationStatus AND e.organiser = :user)')
            ->setParameter('creationStatus', 'En création')
            ->setParameter('user', $user)

            ->andWhere('u.campus = :campus')
            ->setParameter('campus', $user->getCampus()->getId())

            ->getQuery()
            ->getResult();
    }

}
