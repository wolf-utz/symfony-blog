<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ContactRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class ContactRequestRepository.
 */
class ContactRequestRepository extends AbstractRespoitory
{
    /**
     * PostRepository constructor.
     *
     * @param RegistryInterface      $registry
     * @param EntityManagerInterface $em
     */
    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, $em, ContactRequest::class);
    }
}
