<?php

declare(strict_types=1);

namespace App\Repository;

use App\Exception\WrongEntityClassException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class AbstractRespoitory.
 */
class AbstractRespoitory extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface|null
     */
    protected $em = null;

    /**
     * @var string
     */
    protected $class = "";

    /**
     * PostRepository constructor.
     *
     * @param RegistryInterface      $registry
     * @param EntityManagerInterface $em
     * @param string                 $class
     */
    public function __construct(RegistryInterface $registry, EntityManagerInterface $em, string $class = "")
    {
        parent::__construct($registry, $class);
        $this->em = $em;
        $this->class = $class;
    }

    /**
     * @param $object
     *
     * @throws WrongEntityClassException
     */
    public function add($object)
    {
        if(get_class($object) !== $this->class) {
            throw new WrongEntityClassException(
                "The given object is not of type ".$this->class." this repository manages. The given object is of type: ".get_class($object),
                1517768742
            );
        }
        $this->em->persist($object);
        $this->em->flush();
    }

    /**
     * @param $object
     *
     * @throws WrongEntityClassException
     */
    public function remove($object)
    {
        if(get_class($object) !== $this->class) {
            throw new WrongEntityClassException(
                "The given object is not of type ".$this->class." this repository manages. The given object is of type: ".get_class($object),
                1517768742
            );
        }
        $this->em->remove($object);
        $this->em->flush();
    }

    /**
     * @param $object
     *
     * @throws WrongEntityClassException
     */
    public function update($object)
    {
        if(get_class($object) !== $this->class) {
            throw new WrongEntityClassException(
                "The given object is not of type ".$this->class." this repository manages. The given object is of type: ".get_class($object),
                1517768742
            );
        }
        $this->em->merge($object);
        $this->em->flush();
    }
}
