<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function add(Task $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Task $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }



    public function findByFilter($filter)
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->where('t.project = :project')
            ->setParameter('project', $filter['project']);
         
        
        if($filter['isCompleted'] !== null) {
            $queryBuilder
                ->andWhere('t.isCompleted = :isCompleted')
                ->setParameter('isCompleted', $filter['isCompleted']);
        }    

        $query = $queryBuilder->getQuery();

        return $query->execute();
    }

    public function findTodayTasks()
    {
        $currentDate = new \DateTime();
        $startDate = clone $currentDate->setTime(0,0,0);
        $endDate = clone $currentDate->setTime(23,59,59);


        $queryBuilder = $this->createQueryBuilder('t');
        $queryBuilder->where('t.dueDate >= :startDay AND t.dueDate < :endDay');

        $queryBuilder->setParameter('startDay', $startDate);
        $queryBuilder->setParameter('endDay', $endDate);

        $query = $queryBuilder->getQuery();

        return $query->execute();

    }   

//    /**
//     * @return Task[] Returns an array of Task objects
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

//    public function findOneBySomeField($value): ?Task
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
