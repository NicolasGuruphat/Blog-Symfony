<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Category[]    findAllPostsNotNull()
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @return Category[]
     */
    public function findAllPostsNotNull(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT c
            FROM App\Entity\Category c
            WHERE c.posts is not empty'
        );

        return $query->getResult();
    }

    /**
     * @return int
     */
    public function findPostsCount(int $categoryId): array
    {
        $entityManager = $this->getEntityManager();

        // $query = $entityManager->createQuery(
        //     'SELECT count(p)
        //     FROM App\Entity\Post p
        //     WHERE p.categories in 
        //     (select c
        //     FROM App\Entity\Category c
        //     WHERE c.id = :id)'
        // )->setParameter('id', $categoryId);

        $query = $entityManager->createQuery(
            'SELECT count(p)
            FROM App\Entity\Post p
            JOIN p.categories c
            WHERE p.id = :id'
        )->setParameter('id', $categoryId);

        return $query->getResult();
    }
}
