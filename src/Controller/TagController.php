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

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;

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
     * @Route("/tags", name="tag_list")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list()
    {
        return $this->render('tag/list.html.twig', [
            'tags' => $this->tagRepository->findAllEvenHidden()
        ]);
    }

    /**
     * @Route("/tag/{slug}", name="tag_tag")
     *
     * @ParamConverter("tag", class="App\Entity\Tag")
     *
     * @param Tag $tag
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tag(Tag $tag)
    {
        return $this->render('tag/tag.html.twig', [
            'tag' => $tag,
            'posts' => $tag->getPosts()
        ]);
    }
}