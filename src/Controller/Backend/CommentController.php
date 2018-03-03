<?php

declare(strict_types=1);

namespace App\Controller\Backend;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentController.
 */
class CommentController extends Controller
{
    /**
     * @var PostRepository|null
     */
    protected $postRepository = null;

    /**
     * PostController constructor.
     *
     * @param PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @Route("/backend/comments", name="backend_comment_list")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list()
    {
        return $this->render('backend/comment/list.html.twig', []);
    }
}
