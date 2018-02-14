<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PaginationPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class PostController.
 */
class PostController extends Controller
{
    /**
     * @var PaginationPostRepository|null
     */
    protected $paginationPostRepository = null;

    /**
     * PostController constructor.
     *
     * @param PaginationPostRepository $repo
     */
    public function __construct(PaginationPostRepository $repo)
    {
        $this->paginationPostRepository = $repo;
    }

    /**
     * This action shows a list of recent posts.
     *
     * @Route("/", name="post_list")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list(Request $request)
    {
        $currentPage = $request->query->get('currentPage') > 0 ? $request->query->get('currentPage') : 1;
        $limit = 3; // TODO: get this by configuration.
        $posts = $this->paginationPostRepository->findAllVisiblePaginated($currentPage, $limit);
        $maxPages = ceil($posts->count() / $limit);

        return $this->render('post/list.html.twig', [
            'posts' => $posts,
            'currentPage' => $currentPage,
            'limit' => $limit,
            'maxPages' => $maxPages,
        ]);
    }

    /**
     * This action shows a list of recent posts.
     *
     * @Route("/posts/page/{currentPage}", name="post_ajax_list")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ajaxList(int $currentPage = 1)
    {
        $limit = 3; // TODO: get this by configuration.
        $posts = $this->paginationPostRepository->findAllVisiblePaginated($currentPage, $limit);

        return $this->render('post/ajax_list.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/post/{slug}", name="post_show")
     * @ParamConverter("post", class="App\Entity\Post")
     *
     * @param Post $post
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Post $post)
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }
}
