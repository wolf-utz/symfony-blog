<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class TagRepository.
 */
class TagRepository extends AbstractRespoitory
{
    /**
     * PostRepository constructor.
     *
     * @param RegistryInterface      $registry
     * @param EntityManagerInterface $em
     */
    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, $em, Tag::class);
    }

    /**
     * @return array|mixed
     */
    public function findAll()
    {
        return $this->createQueryBuilder('tag')
            ->orderBy('tag.title', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
