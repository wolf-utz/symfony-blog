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

namespace App\Tests\Functional;

use App\Tests\DatabasePrimer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class DatabaseTestCase.
 */
class DatabaseTestCase extends KernelTestCase
{
    /**
     * Set up.
     */
    public static function setUpBeforeClass()
    {
        self::bootKernel();
        DatabasePrimer::prime(self::$kernel);
    }

    /**
     * Setup.
     */
    protected function setUp()
    {
        self::bootKernel();
    }

    /**
     * Tear down.
     */
    public static function tearDownAfterClass()
    {
        self::bootKernel();
        DatabasePrimer::tearDown(self::$kernel);
        self::ensureKernelShutdown();
    }
}