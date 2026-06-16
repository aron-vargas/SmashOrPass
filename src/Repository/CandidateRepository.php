<?php

namespace App\Repository;

use App\Entity\Candidate;
use App\Entity\User;
use App\Entity\UserVote;
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
     * Find the next Candidate in ascending order of id optionally constrained by category and gender.
     * If a seed is provided, it determines a deterministic starting point; the next record after that start is returned.
     * When a User is supplied, the method will prefer candidates the user has NOT voted on; if none are available it will return the next candidate regardless.
     */
    public function findByCategoryAndGender(?string $categoryName, ?string $gender, ?User $user = null, ?int &$currentIndex = null): ?Candidate
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c.id')
            ->orderBy('c.id', 'ASC');

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

        // Normalize ids
        $ids = array_map(fn($r) => (int) (isset($r['id']) ? $r['id'] : array_values($r)[0]), $results);
        $count = count($ids);

        // index from currentIndex (if provided)
        if ($currentIndex === null)
            $currentIndex = 0;
        else if ($currentIndex >= $count)
            $currentIndex = 0;

        return $this->find($ids[$currentIndex]);
    }

    /**
     * Find the Candidates in ascending order of id optionally constrained by category and gender.
     */
    public function findAllByCategoryAndGender(?string $categoryName, ?string $gender): ?array
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.Categories', 'cat')
            ->addSelect('cat')
            ->orderBy('c.id', 'ASC');

        if ($categoryName)
        {
            $qb->andWhere('cat.Name = :category')
                ->setParameter('category', $categoryName);
        }

        if ($gender)
        {
            $qb->andWhere('c.Gender = :gender')
                ->setParameter('gender', $gender);
        }

        return $qb->getQuery()->getResult();
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
