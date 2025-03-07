<?php

namespace App\Repository;

use App\data\EventSearch;
use App\Entity\Event;
use App\Entity\User;
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

    /**
     * @throws \Exception
     */
    public function findSearch(EventSearch $search, User $user)
    {

        $query = $this
            ->createQueryBuilder('p')
            ->leftJoin('p.organiser', 'u')
            ->andWhere('p.status != :creationStatus OR (p.status = :creationStatus AND p.organiser = :user)')
            ->setParameter('creationStatus', 'En création')
            ->setParameter('user', $user)
            ->andWhere('p.status = :enCours OR p.status = :cloturee OR p.status = :enCrea  OR p.status = :ouverte ')
            ->setParameter('enCours', 'En cours')
            ->setParameter('cloturee', 'Clôturée')
            ->setParameter('ouverte', 'Ouverte')
            ->setParameter('enCrea', 'En création');

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

        /* Recherche par date de début */
        if (!empty($search->startDate)) {
             $query = $query
                 ->andWhere('p.startingDate >= :startDateMin')
                 ->setParameter('startDateMin', $search->startDate);
        }
        if (!empty($search->endDate)) {
             $endDate = $search->endDate;
             $endDate->modify('+1 day');
             $query = $query
                 ->andWhere('p.startingDate <= :startDateMax')
                 ->setParameter('startDateMax',$endDate);
        }

        /* Recherche par sortie organisé par soi-même */
        if (!empty($search->isOrganizer)){
            $query->andWhere('p.organiser = :user')
                ->setParameter('user', $user);
        }

        /* Recherche par sortie à laquelle on participe  */
        if (!empty($search->isRegistered)) {
            $query->andWhere(':user MEMBER OF p.participants')
                ->setParameter('user', $user);
        }

        /* Recherche par sortie à laquelle on ne participe pas */
        if (!empty($search->notRegistered)){
            $currentDate = new \DateTime();
            $currentDate->modify('-1 day');
            $query->andWhere(':user NOT MEMBER OF p.participants')
                ->setParameter('user', $user)
                ->andWhere('p.registrationEnd >= :currentDate')
                ->setParameter('currentDate', $currentDate);
        }

        /* Recherche par sortie par terminées récemment */
        if (!empty($search->pastEvent)){
            $query->andWhere('p.status = :end')
                ->setParameter('end', "Terminée");
        }


        return $query->getQuery()->getResult();

    }

}
