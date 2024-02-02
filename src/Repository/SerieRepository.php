<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serie>
 * @method Serie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Serie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Serie[]    findAll()
 * @method Serie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }

    public function findBestSeries()
    {   /*
        //REQUETE EN DQL
        $entityManager = $this->getEntityManager();
        $dql ="
              SELECT s 
              FROM App\Entity\Serie s
              WHERE s.popularity > 100
              AND s.vote > 8
              ORDER BY s.popularity DESC
        ";
        $query = $entityManager->createQuery($dql);

        */
        //REQUETE EN QUERYBUILDER
        $queryBuilder = $this->createQueryBuilder('s');

        $queryBuilder->leftJoin('s.seasons', 'seas') //Réduire le nombre de requêtes
            ->addSelect('seas');

        $queryBuilder->andWhere('s.popularity > 100');
        $queryBuilder->andWhere('s.vote > 8');
        $queryBuilder->addOrderBy('s.popularity', 'DESC');
        $query = $queryBuilder ->getQuery();


        $query->setMaxResults(50); //Nombres max. de résultats récupérés

        $paginator = new Paginator($query);

        //$results = $query->getResult(); //Tableau qui récupère tous les éléments
        return $paginator;
    }

    /* public function add(Serie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Serie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }*/
}
