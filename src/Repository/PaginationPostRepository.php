<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2018 Wolf Utz <utz@riconet.de>, riconet
 *      Created on: 10.02.18 21:53
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class PaginationPostRepository.
 */
class PaginationPostRepository extends PostRepository
{
    /**
     * PostRepository constructor.
     *
     * @param RegistryInterface      $registry
     * @param EntityManagerInterface $em
     */
    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, $em);
    }

    /**
     * @param int $currentPage
     * @param int $limit
     *
     * @return mixed
     */
    public function findAllPaginated($currentPage = 1, $limit = 5)
    {
        /** @var QueryBuilder $query */
        $query = $this->createQueryBuilder('p');
        $query->orderBy('p.created', 'DESC')
              ->getQuery();

        return $this->paginate($query, $currentPage, $limit);
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
