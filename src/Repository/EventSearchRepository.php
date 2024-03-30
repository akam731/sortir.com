<?php

namespace App\Repository;

use App\data\EventSearch;
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
class EventSearchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findSearch(EventSearch $search)
    {

        $query = $this
            ->createQueryBuilder('p')
            ->leftJoin('p.organiser', 'u');

        /* Recherche par campus */
        if (!empty($search->campus)){
            $query->andWhere('u.campus = :campus')
            ->setParameter('campus', $search->campus->getId());
        }

        /* Recherche par nom */
        if (!empty($search->q)){
            $query = $query
                ->andWhere('p.name LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        /* Recherche par dâte de début */
        if (!empty($search->startDate)){
            $query = $query
                ->andWhere('e.starting_date >= :startDate')
                ->setParameter('startDate', $search->startDate);
        }

        return $query->getQuery()->getResult();

    }

}
