<?php

declare(strict_types=1);

namespace App\Controller\Backend;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Repository\PostRepository;
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
     * @Route("/backend/posts/{currentPage}", name="backend_post_list")
     *
     * @param int $currentPage
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list($currentPage = 1)
    {
        $limit = 10;
        $posts = $this->postRepository->findAllPaginated($currentPage, $limit);
        $maxPages = ceil($posts->count() / $limit);

        return $this->render('backend/post/list.html.twig', [
            'posts' => $posts,
            'currentPage' => $currentPage,
            'limit' => $limit,
            'maxPages' => $maxPages,
        ]);
    }

    /**
     * @Route("/backend/post/show/{id}", name="backend_post_show")
     *
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
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \App\Exception\WrongEntityClassException
     */
    public function create(Request $request)
    {
        $form = $this->createForm(PostType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            /** @var Post $post */
            $post = $form->getData();
            if (empty($post->getSlug())) {
                $post->generateSlug();
            }
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
     * @Route("/backend/post/edit/{id}", name="backend_post_edit")
     *
     * @ParamConverter("post", class="App\Entity\Post")
     *
     * @param Request $request
     * @param Post    $post
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, Post $post)
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        return $this->render('backend/post/edit.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }

    /**
     * @Route("/backend/post/update/{id}", name="backend_post_update")
     *
     * @param Request $request
     * @param Post    $post
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \App\Exception\WrongEntityClassException
     */
    public function update(Request $request, Post $post)
    {
        $form = $this->createForm(PostType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Post $data */
            $data = $form->getData();
            $post->setTitle($data->getTitle());
            $post->setTeaser($data->getTeaser());
            $post->setTags($data->getTags());
            $post->setSlug($data->getSlug());
            $post->setBody($data->getBody());
            $post->setLastUpdated();
            $post->setHidden($data->isHidden());
            $post->setEnableComments($data->isEnableComments());
            $this->postRepository->update($post);
            $this->addFlash(
                'success',
                'Successfully update the post with id '.$post->getId()
            );

            return $this->redirectToRoute('backend_post_list');
        } else {
            return $this->redirectToRoute('backend_post_edit', ['request' => $request, 'post' => $post]);
        }
    }

    /**
     * @Route("/backend/post/delete/{id}", name="backend_post_delete")
     *
     * @ParamConverter("post", class="App\Entity\Post")
     *
     * @param Post    $post
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @throws \App\Exception\WrongEntityClassException
     */
    public function delete(Post $post, Request $request)
    {
        $currentPage = $request->get('currentPage');
        $this->postRepository->remove($post);
        $this->addFlash(
            'info',
            'Successfully removed post '.$post->getTitle().'!'
        );

        return $this->redirectToRoute('backend_post_list', ['currentPage' => $currentPage, '_fragment' => 'post-'.$post->getId()]);
    }

    /**
     * @Route("/backend/post/toggle-hidden-state/{id}", name="backend_post_toggle_hidden_state")
     *
     * @ParamConverter("post", class="App\Entity\Post")
     *
     * @param Post    $post
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \App\Exception\WrongEntityClassException
     */
    public function toggleHiddenState(Post $post, Request $request)
    {
        $currentPage = $request->get('currentPage');
        $post->setHidden(!$post->isHidden());
        $this->postRepository->update($post);
        $this->addFlash(
            'info',
            'Successfully toggled hidden state of post '.$post->getTitle().'!'
        );

        return $this->redirectToRoute('backend_post_list', ['currentPage' => $currentPage, '_fragment' => 'post-'.$post->getId()]);
    }
}
