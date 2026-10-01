<?php

namespace App\Repository;

use App\Entity\Trip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\City;

/**
 * @extends ServiceEntityRepository<Trip>
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    /** @return Trip[] */
    public function search(City $origin, City $destination, \DateTimeImmutable $day): array
    {
        $start = $day->setTime(0, 0); //$startOfDay force l'heure à 00:00:00 (début de journée).
        $end = $start->modify('+1 day'); //$endOfDay ajoute 1 jour pour cibler le lendemain à 00:00:00 (fin de journée).

        return $this->createQueryBuilder('t')
            ->andWhere('t.origin = :origin') // initialise ville de départ
            ->andWhere('t.destination = :destination') //initialise ville d'arrivée
            ->andWhere('t.departureAt >= :start') // filtre la ville de départ
            ->andWhere('t.departureAt < :end') // filtre la ville d'arrivée
            ->setParameter('origin', $origin)
            ->setParameter('destination', $destination)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('t.departureAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
