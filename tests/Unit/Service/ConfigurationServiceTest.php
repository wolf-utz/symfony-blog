<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2018 Wolf Utz <utz@riconet.de>, riconet
 *      Created on: 21.02.18 12:20
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

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