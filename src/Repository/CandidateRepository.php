<?php

namespace App\Repository;

use App\Entity\Candidate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Candidate>
 */
class CandidateRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidate::class);
    }

    /**
     * Find a random Candidate optionally constrained by category name and gender.
     * If a seed is provided, selection will be deterministic for that seed.
     */
    public function findRandomByCategoryAndGender(?string $categoryName, ?string $gender, ?int $seed = null): ?Candidate
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c.id');

        if ($categoryName)
        {
            $qb->join('c.Categories', 'cat')
                ->andWhere('cat.Name = :category')
                ->setParameter('category', $categoryName);
        }

        if ($gender)
        {
            $qb->andWhere('c.Gender = :gender')
                ->setParameter('gender', $gender);
        }

        $results = $qb->getQuery()->getScalarResult();

        if (empty($results))
        {
            return null;
        }

        // Normalize ids (results may be [['id' => '123'], ...] or [['0' => '123'], ...])
        $ids = array_map(fn($r) => (int) (isset($r['id']) ? $r['id'] : array_values($r)[0]), $results);
        $index = random_int(0, count($ids) - 1);

        return $this->find($ids[$index]);
    }

    //    /**
    //     * @return Candidate[] Returns an array of Candidate objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Candidate
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
