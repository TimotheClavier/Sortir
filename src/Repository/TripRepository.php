<?php

namespace App\Repository;

use App\Entity\City;
use App\Entity\Trip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;


class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    public function findByCity(City $city)
    {
        return $this->createQueryBuilder('trip')
            ->join('trip.place', 'place')
            ->join('place.city', 'city')
            ->where('city = :city')
            ->setParameter('city', $city)
            ->getQuery()->getResult();
    }

    public function findByName(string $name)
    {
        return $this->createQueryBuilder('trip')
            ->where('trip.name like :name')
            ->setParameter('name', '%' . $name . '%')
            ->getQuery()->getResult();
    }

    public function findByCityAndName(City $city,string $search)
    {
        return $this->createQueryBuilder('trip')
            ->join('trip.place', 'place')
            ->join('place.city', 'city')
            ->where('city = :city')
            ->andwhere('trip.name like :name')
            ->setParameter('city', $city)
            ->setParameter('name', '%' . $search . '%')
            ->getQuery()->getResult();
    }

    public function findPast()
    {
        return $this->createQueryBuilder('trip')
            ->where('trip.status = 5')
            ->getQuery()->getResult();
    }

    public function findPastByCity(City $city)
    {
        return $this->createQueryBuilder('trip')
            ->join('trip.place', 'place')
            ->join('place.city', 'city')
            ->where('city = :city')
            ->setParameter('city', $city)
            ->andWhere('trip.status = 5')
            ->getQuery()->getResult();
    }
}
