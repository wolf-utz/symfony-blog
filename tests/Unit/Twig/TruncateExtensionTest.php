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

namespace App\Tests\Unit\Twig;

use App\Twig\TruncateExtension;
use PHPUnit\Framework\TestCase;

/**
 * Class TruncateExtensionTest.
 */
class TruncateExtensionTest extends TestCase
{
    /**
     * @var null|TruncateExtension
     */
    private $subject = null;

    /**
     * @var string
     */
    private $fixtureText = "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam.";

    /**
     * Set up.
     */
    protected function setUp()
    {
        $this->subject = new TruncateExtension();
    }

    /**
     * @test
     */
    public function testReturnFiltersReturnFilter()
    {
        $this->assertEquals(get_class($this->subject->getFilters()[0]), \Twig_Filter::class);
    }

    /**
     * @test
     */
    public function testTruncateWithoutWordBoundary()
    {
        $truncatedText = $this->subject->truncateFilter($this->fixtureText, 10, false, '#');
        $this->assertEquals('Lorem ipsu#', $truncatedText);
    }

    /**
     * @test
     */
    public function testTruncateWithWordBoundary()
    {
        $truncatedText = $this->subject->truncateFilter($this->fixtureText, 20, true, '?');
        $this->assertEquals('Lorem ipsum dolor sit?', $truncatedText);
    }
}