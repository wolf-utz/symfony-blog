<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class CommentRepository.
 */
class CommentRepository extends AbstractRespoitory
{
    /**
     * PostRepository constructor.
     *
     * @param RegistryInterface      $registry
     * @param EntityManagerInterface $em
     */
    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, $em, Comment::class);
    }
}
