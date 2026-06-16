<?php

namespace App\Repository;

use App\Entity\UserVote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserVote>
 */
class UserVoteRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserVote::class);
    }

    /**
     * Find user votes filtered by candidate and/or user id.
     * Pass null for either parameter to indicate "all".
     *
     * @return UserVote[]
     */
    public function findFiltered(?int $candidateId, ?int $userId): array
    {
        $qb = $this->createQueryBuilder('u')
            ->leftJoin('u.Candidate', 'c')
            ->leftJoin('u.User', 'usr')
            // Select the User explicitly to avoid extra lazy loads, but omit selecting Candidate
            // because selecting Candidate here causes Doctrine's hydrator to attempt hydrating
            // the inverse collection `userVotes` and can trigger undefined array key warnings.
            ->addSelect('usr')
            ->orderBy('u.CreatedOn', 'DESC');

        if ($candidateId !== null)
        {
            $qb->andWhere('c.id = :candidateId')
                ->setParameter('candidateId', $candidateId);
        }

        if ($userId !== null)
        {
            $qb->andWhere('usr.id = :userId')
                ->setParameter('userId', $userId);
        }

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return UserVote[] Returns an array of UserVote objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?UserVote
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
