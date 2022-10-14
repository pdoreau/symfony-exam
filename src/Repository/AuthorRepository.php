<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * {@inheritDoc}
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function findByLastNameOrFirstName(?string $search)
    {
        $qb = $this->createQueryBuilder('a');
        if ($search) {
            $qb
                ->where("a.firstName LIKE :search or a.lastName LIKE :search")
                ->setParameter('search', '%'.$search.'%')
            ;
        }

        return $qb->getQuery()->getResult();
    }
}
