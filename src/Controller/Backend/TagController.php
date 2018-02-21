<?php

declare(strict_types=1);

namespace App\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class TagController.
 */
class TagController extends Controller
{
    private $paginatedTagRepository = null;


    public function __construct()
    {
    }

    /**
     * @Route("/backend/tags/{currentPage}", name="backend_tag_list")
     *
     * @param int $currentPage
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list($currentPage = 1)
    {
        $limit = 10;
        $posts = $this->paginatedTagRepository->findAllPaginated($currentPage, $limit);
        $maxPages = ceil($posts->count() / $limit);

        return $this->render('backend/post/list.html.twig', [
            'posts' => $posts,
            'currentPage' => $currentPage,
            'limit' => $limit,
            'maxPages' => $maxPages,
        ]);
    }
}