<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Kittie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Kittie>
 *
 * @method Kittie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Kittie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Kittie[]    findAll()
 * @method Kittie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KittieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Kittie::class);
    }
}
