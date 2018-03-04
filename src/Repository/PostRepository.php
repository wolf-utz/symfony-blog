<?php
/**
 * Copyright (c) 2018 Wolf Utz <wpu@hotmail.de>
 *
 * This file is part of the OmegaBlog project.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
