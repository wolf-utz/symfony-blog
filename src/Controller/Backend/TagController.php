<?php
/**
 * Copyright (c) 2018 Wolf Utz <wpu@hotmail.de>
 *
 * This file is part of the OmegaBlog project.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Controller\Backend;

use App\Entity\Post;
use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TagController.
 */
class TagController extends Controller
{
    /**
     * @var null|TagRepository
     */
    private $tagRepository = null;

    /**
     * TagController constructor.
     *
     * @param TagRepository $tagRepository
     */
    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * @Route("/backend/tags", name="backend_tag_list")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list(Request $request)
    {
        $form = $this->createForm(TagType::class, new Tag());
        $form->handleRequest($request);

        return $this->render('backend/tag/list.html.twig', [
            'tags' => $this->tagRepository->findAllEvenHidden(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/backend/tag/new", name="backend_tag_create")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \App\Exception\WrongEntityClassException
     */
    public function create(Request $request)
    {
        $form = $this->createForm(TagType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Tag $newTag */
            $newTag = $form->getData();
            if(is_null($this->tagRepository->findOneBy(['title' => $newTag->getTitle()]))) {
                $newTag->generateSlug();
                $this->tagRepository->add($newTag);
                $this->addFlash('success', 'Successfully created the new tag with title: '.$newTag->getTitle());
            } else {
                $this->addFlash('danger', 'Tag with title '.$newTag->getTitle().' already exist!');
            }
        } else {
            $this->addFlash('danger', 'The creation of the new tag has failed!');
        }

        return $this->redirectToRoute('backend_tag_list');
    }

    /**
     * @Route("/backend/tag/remove/{tag}", name="backend_tag_remove")
     *
     * @ParamConverter("tag", class="App\Entity\Tag")
     *
     * @param Tag $tag
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @throws \App\Exception\WrongEntityClassException
     */
    public function remove(Tag $tag)
    {
        $posts = $tag->getPosts();
        /** @var Post $post */
        foreach ($posts as $post) {
            $post->removeTag($tag);
        }
        $this->tagRepository->remove($tag);
        $this->addFlash('success', 'Successfully removed tag with title: '.$tag->getTitle());

        return $this->redirectToRoute('backend_tag_list');
    }
}
