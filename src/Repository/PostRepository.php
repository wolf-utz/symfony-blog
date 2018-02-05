<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class PostRepository.
 */
class PostRepository extends AbstractRespoitory
{
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
    public function findAll()
    {
        return $this->createQueryBuilder('post')
            ->orderBy('post.title', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
