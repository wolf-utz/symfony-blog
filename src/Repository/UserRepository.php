<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class UserRepository.
 */
class UserRepository extends AbstractRespoitory
{
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
     * @return array|mixed
     */
    public function findAll()
    {
        return $this->createQueryBuilder('User')
            ->orderBy('User.title', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
