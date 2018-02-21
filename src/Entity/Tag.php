<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Tag.
 *
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 */
class Tag extends AbstractEntity
{
    /**
     * @ORM\Column(type="string", length=64, unique=true)
     *
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=128, unique=true)
     *
     * @var string
     */
    private $slug = "";

    /**
     * @var bool
     */
    protected $hidden = false;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Post", mappedBy="tags")
     */
    protected $posts;

    /**
     * Tag constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->posts = new ArrayCollection();
    }

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
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Generates the slug.
     */
    public function generateSlug()
    {
        // TODO: Find a better solution!
        $this->slug = rtrim(strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->title))), '-');
    }

    /**
     * @return ArrayCollection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param ArrayCollection $posts
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;
    }

    /**
     * Helper method to remove the mm relation between a post and a tag.
     *
     * @param Post $post
     */
    public function removePost(Post $post)
    {
        if (!$this->posts->contains($post)) {
            return;
        }
        $this->posts->removeElement($post);
        $post->removeTag($this);
    }
}
