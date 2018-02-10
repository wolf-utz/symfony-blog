<?php
namespace App\Tests\Entity;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * Class CategoryTest.
 */
class CategoryTest extends TestCase
{
    /**
     * Test the accessor for property title.
     * @test
     */
    public function testSetTitleAccessors()
    {
        $subject = new Category();
        $title = "Test title";
        $this->assertEquals($subject->getTitle(), "");
        $subject->setTitle($title);
        $this->assertEquals($subject->getTitle(), $title);
    }

    /**
     * Test the accessor for property parent.
     * @test
     */
    public function testSetParentAccessors()
    {
        $subject = new Category();
        $parent = new Category();
        $this->assertEquals($subject->getParent(), null);
        $subject->setParent($parent);
        $this->assertEquals($subject->getParent(), $parent);
    }
}