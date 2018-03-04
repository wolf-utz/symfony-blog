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
