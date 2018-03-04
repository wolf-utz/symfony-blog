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

namespace App\Tests\Unit\Service;

use App\Exception\ConfigurationNotFoundException;
use App\Service\ConfigurationService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class ConfigurationServiceTest.
 */
class ConfigurationServiceTest extends TestCase
{
    /**
     * @var null|ConfigurationService
     */
    private $subject = null;

    /**
     * Set up.
     */
    protected function setUp()
    {
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->setConstructorArgs([])
            ->getMock();
        $kernelInterface = $this->getMockBuilder(KernelInterface::class)
            ->setConstructorArgs([])
            ->getMock();
        $kernelInterface->expects($this->any())
            ->method('getRootDir')
            ->will($this->returnValue(__DIR__.'/../Fixtures/config'));
        $container->expects($this->any())
            ->method('get')
            ->will($this->returnValue($kernelInterface));

        $this->subject = new ConfigurationService($container);
    }

    /**
     * @test
     */
    public function testConfigurationFileWasSuccessfullyRead()
    {
        $configuration = $this->subject->getConfiguration();
        $this->assertTrue(count($configuration) > 0);
    }

    /**
     * @test
     * @throws ConfigurationNotFoundException
     */
    public function testGetConfigurationEntry()
    {
        try {
            $value = $this->subject->getConfigurationEntry("title");
            $this->assertEquals($value, 'A Blog');
        } catch (ConfigurationNotFoundException $e) {
            throw $e;
        }
    }

    /**
     * @test
     */
    public function testUpdateConfigurationUpdatesEntries()
    {
        $newDescription = 'Description was updated! Still, this is a blog!';
        $newConfiguration = $this->subject->getConfiguration();
        $oldDescription = $newConfiguration['description'];
        $newConfiguration['description'] = $newDescription;
        $this->subject->updateConfiguration($newConfiguration);
        $updatedConfiguration = $this->subject->getConfiguration();
        $this->assertEquals($updatedConfiguration['description'], $newDescription);
        $updatedConfiguration['description'] = $oldDescription;
        $this->subject->updateConfiguration($updatedConfiguration);
        $originalConfiguration =  $this->subject->getConfiguration();
        $this->assertEquals($originalConfiguration['description'], $oldDescription);
    }
}