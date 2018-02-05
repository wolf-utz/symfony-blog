<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PostController.
 */
class PostController extends Controller
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
     * @Route("/posts", name="post_list")
     */
    public function list()
    {
        $posts = $this->postRepository->findAll();

        return $this->render('post/list.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/posts/show/{slug}", name="post_show")
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

    /**
     * @Route("/posts/new", name="post_new")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $form = $this->createForm(PostType::class, new Post(), [
            'action' => $this->generateUrl('post_create'),
        ]);
        $form->handleRequest($request);

        return $this->render('post/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/posts/create", name="post_create")
     *
     * @param Request                $request
     * @param EntityManagerInterface $em
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \App\Exception\WrongEntityClassException
     */
    public function create(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(PostType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $this->postRepository->add($post);
            $this->addFlash(
                'info',
                'Successfully created new post!'
            );

            return $this->redirectToRoute('post_list');
        } else {
            return $this->redirectToRoute('post_new', ['request' => $request]);
        }
    }
}
