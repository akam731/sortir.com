<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<City>
 *
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, City::class);
        $this->entityManager = $entityManager;
    }

    public function rechercherObjetsParChaine($chaine)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('e')
            ->from(Campus::class, 'e')
            ->where($queryBuilder->expr()->like('e.name', ':chaine'))
            ->setParameter('chaine', '%' . $chaine . '%');

        return $queryBuilder->getQuery()->getResult();
    }
}
