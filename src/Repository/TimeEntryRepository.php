<?php

namespace App\Repository;

use App\Entity\TimeEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TimeEntry>
 */
class TimeEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TimeEntry::class);
    }

    public function findByUserAndDateRange($user, \DateTime $startDate, \DateTime $endDate): int
    {
        // Fetch all relevant time entries that overlap with the given range
        $timeEntries = $this->createQueryBuilder('t')
            ->andWhere('t.user = :user')
            ->andWhere(
                '(t.clockIn BETWEEN :startDate AND :endDate OR t.clockOut BETWEEN :startDate AND :endDate OR 
                (t.clockIn <= :startDate AND t.clockOut >= :endDate))'
            )
            ->setParameter('user', $user)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();

        // Calculate total time within range
        $totalSeconds = 0;

        foreach ($timeEntries as $entry) {
            // Determine the actual overlap with the range
            $clockIn = $entry->getClockIn();
            $clockOut = $entry->getClockOut() ?: new \DateTime(); // If Clock-Out is null (still clocked in)

            // Calculate overlap period
            $overlapStart = max($startDate, $clockIn); // Later of startDate or clockIn
            $overlapEnd = min($endDate, $clockOut);   // Earlier of endDate or clockOut

            // Only count duration if there's an actual overlap
            if ($overlapStart < $overlapEnd) {
                $totalSeconds += $overlapEnd->getTimestamp() - $overlapStart->getTimestamp();
            }
        }

        return $totalSeconds; // Total time in seconds
    }


    //    /**
    //     * @return TimeEntry[] Returns an array of TimeEntry objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TimeEntry
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
