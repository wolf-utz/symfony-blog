<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Comment.
 *
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment extends AbstractEntity
{
    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $commentator = '';

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $body = '';

    /**
     * @OneToMany(targetEntity="Comment", mappedBy="parent", cascade={"persist", "remove"})
     *
     * @var ArrayCollection|null
     */
    private $children = null;

    /**
     * @ManyToOne(targetEntity="Comment", inversedBy="children")
     * @JoinColumn(name="parent_id", referencedColumnName="id")
     * @JoinColumn(nullable=true)
     *
     * @var Comment|null
     */
    private $parent = null;

    /**
     * @ManyToOne(targetEntity="App\Entity\Post", inversedBy="comments")
     * @JoinColumn(nullable=true)
     *
     * @var Post|null
     */
    private $post = null;

    /**
     * @var bool
     */
    protected $hidden = false;

    /**
     * Comment constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->children = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getCommentator()
    {
        return $this->commentator;
    }

    /**
     * @param string $commentator
     */
    public function setCommentator($commentator)
    {
        $this->commentator = $commentator;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return null|ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param ArrayCollection $children
     */
    public function setChildren(ArrayCollection $children)
    {
        $this->children = $children;
    }

    /**
     * @return null|Comment
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Comment $parent
     */
    public function setParent(Comment $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return Post|null
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param Post $post
     */
    public function setPost(Post $post)
    {
        $this->post = $post;
    }
}
