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

namespace App\Tests\Functional\Controller\Backend;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Functional\DatabaseTestCase;
use Doctrine\ORM\EntityManager;

/**
 * Class UserControllerTest.
 */
class UserRepositoryTest extends DatabaseTestCase
{

    /**
     * @var UserRepository|null;
     */
    private $subject = null;

    /**
     * Setup.
     */
    protected function setUp()
    {
        parent::setUp();
        /** @var EntityManager $em */
        $em = $entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
        $this->subject = $em->getRepository(User::class);
    }

    /**
     * @test
     * @throws \App\Exception\WrongEntityClassException
     */
    public function testAddUserAddsUser()
    {
        $username = "hans_test_case";
        $user = new User();
        $user->setUsername($username);
        $user->setEmail("email@email5.com");
        $this->subject->add($user);
        $this->assertTrue(!is_null($this->subject->findOneBy(["username" => $username])));
    }

    /**
     * @depends testAddUserAddsUser
     */
    public function testUserHasEmail()
    {
        $this->assertTrue(!is_null($this->subject->findOneBy(["email" => 'email@email5.com'])));
    }
}