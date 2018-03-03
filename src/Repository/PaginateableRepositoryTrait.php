<?php
declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Trait PaginateableRepositoryTrait.
 */
trait PaginateableRepositoryTrait
{
    /**
     * @param QueryBuilder $dql
     * @param int          $page
     * @param int          $limit
     *
     * @return Paginator
     */
    public function paginate($dql, $page = 1, $limit = 5)
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1)) // Offset
            ->setMaxResults($limit); // Limit

        return $paginator;
    }
}
