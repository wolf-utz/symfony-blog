<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Category.
 *
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category extends AbstractEntity
{
    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var null|Category
     */
    private $parent = null;

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return Category|null
     */
    public function getParent() : Category
    {
        return $this->parent;
    }

    /**
     * @param Category|null $parent
     */
    public function setParent(Category $parent) : void
    {
        $this->parent = $parent;
    }
}
