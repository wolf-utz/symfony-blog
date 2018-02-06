<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class CategoryRepository.
 */
class CategoryRepository extends AbstractRespoitory
{
    /**
     * PostRepository constructor.
     *
     * @param RegistryInterface      $registry
     * @param EntityManagerInterface $em
     */
    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, $em, Category::class);
    }

    /**
     * @return array|mixed
     */
    public function findAll()
    {
        return $this->createQueryBuilder('Category')
            ->orderBy('Category.title', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
