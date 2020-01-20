<?php
/**
 * Copyright (c) 2018 Wolf Utz <wpu@hotmail.de>.
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

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends AbstractRespoitory
{
    use PaginateableRepositoryTrait;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
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
        $queryBuilder = $this->createQueryBuilder('u');
        $queryBuilder->getQuery();

        return $this->paginate($queryBuilder, $currentPage, $limit);
    }
}
