<?php

namespace App\Repository;

use App\Entity\SchoolWorkshop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SchoolWorkshop>
 *
 * @method SchoolWorkshop|null find($id, $lockMode = null, $lockVersion = null)
 * @method SchoolWorkshop|null findOneBy(array $criteria, array $orderBy = null)
 * @method SchoolWorkshop[]    findAll()
 * @method SchoolWorkshop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SchoolWorkshopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SchoolWorkshop::class);
    }

    public function save(SchoolWorkshop $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SchoolWorkshop $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
