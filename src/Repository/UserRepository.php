<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class UserRepository.
 */
class UserRepository extends AbstractRespoitory
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
        parent::__construct($registry, $em, User::class);
    }

    /**
     * @param int $currentPage
     * @param int $limit
     *
     * @return mixed
     */
    public function findAllEvenHiddenPaginated($currentPage = 1, $limit = 5)
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->createQueryBuilder("u");
        $queryBuilder->getQuery();

        return $this->paginate($queryBuilder, $currentPage, $limit);
    }
}
