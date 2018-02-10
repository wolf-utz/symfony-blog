<?php

declare(strict_types=1);

namespace App\Controller\Backend;

use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
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
     * @Route("/backend/posts", name="backend_post_list")
     */
    public function list()
    {
        $posts = $this->postRepository->findAllEvenHidden();

        return $this->render('backend/post/list.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/backend/post/show/{id}", name="backend_post_show")
     * @ParamConverter("post", class="App\Entity\Post")
     *
     * @param Post $post
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Post $post)
    {
        return $this->render('backend/post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/backend/post/new", name="backend_post_new")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $form = $this->createForm(PostType::class, new Post(), [
            'action' => $this->generateUrl('backend_post_create'),
        ]);
        $form->handleRequest($request);

        return $this->render('backend/post/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/backend/post/create", name="backend_post_create")
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
            /** @var User $user */
            $user = $this->getUser();
            /** @var Post $post */
            $post = $form->getData();
            $post->setUser($user);
            $this->postRepository->add($post);
            $this->addFlash(
                'info',
                'Successfully created new post!'
            );

            return $this->redirectToRoute('backend_post_list');
        } else {
            return $this->redirectToRoute('backend_post_new', ['request' => $request]);
        }
    }

    /**
     * @Route("/posts/toggle-hidden-state/{id}", name="backend_post_toggle_hidden_state")
     * @ParamConverter("post", class="App\Entity\Post")
     *
     * @param Post $post
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \App\Exception\WrongEntityClassException
     */
    public function toggleHiddenState(Post $post)
    {
        $post->setHidden(!$post->isHidden());
        $this->postRepository->update($post);
        $this->addFlash(
            'info',
            'Successfully toggled hidden state of post '.$post->getTitle().'!'
        );

        return $this->redirectToRoute('backend_post_list', ['_fragment' => 'post-'.$post->getId()]);
    }
}
