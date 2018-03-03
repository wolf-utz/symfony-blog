<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class PostRepository.
 */
class PostRepository extends AbstractRespoitory
{
    use PaginateableRepositoryTrait;

    /**
     * PostRepository constructor.
     *
     * @param RegistryInterface      $registry
     * @param EntityManagerInterface $em
     */
    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, $em, Post::class);
    }

    /**
     * @return array|mixed
     */
    public function findAllEvenHidden()
    {
        return $this->createQueryBuilder('post')
            ->orderBy('post.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return mixed
     */
    public function findAllVisible()
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->createQueryBuilder('post');

        return $queryBuilder->where($queryBuilder->expr()->eq('post.hidden', ':flag'))
            ->orderBy('post.title', 'ASC')
            ->setParameter('flag', false)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $limit
     *
     * @return mixed
     */
    public function findRecent($limit = 5)
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->createQueryBuilder('post');

        return $queryBuilder->where($queryBuilder->expr()->eq('post.hidden', ':flag'))
            ->orderBy('post.created', 'DESC')
            ->setParameter('flag', false)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $currentPage
     * @param int $limit
     *
     * @return mixed
     */
    public function findAllPaginated($currentPage = 1, $limit = 5)
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->createQueryBuilder('p');
        $queryBuilder->orderBy('p.created', 'DESC')
            ->getQuery();

        return $this->paginate($queryBuilder, $currentPage, $limit);
    }

    /**
     * @param int $currentPage
     * @param int $limit
     *
     * @return mixed
     */
    public function findAllVisiblePaginated($currentPage = 1, $limit = 5)
    {
        /** @var QueryBuilder $query */
        $query = $this->createQueryBuilder('p');
        $query->where($query->expr()->eq('p.hidden', ':flag'))
            ->orderBy('p.created', 'DESC')
            ->setParameter('flag', false)
            ->getQuery();

        return $this->paginate($query, $currentPage, $limit);
    }
}
