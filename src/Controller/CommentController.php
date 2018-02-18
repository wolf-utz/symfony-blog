<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2018 Wolf Utz <utz@riconet.de>, riconet
 *      Created on: 15.02.18 21:42
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Service\ConfigurationService;
use ReCaptcha\ReCaptcha;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class CommentController.
 */
class CommentController extends Controller
{
    /**
     * @var null|CommentRepository
     */
    private $commentRepository = null;

    /**
     * @var null|ConfigurationService
     */
    private $configurationService = null;

    /**
     * CommentController constructor.
     *
     * @param CommentRepository $commentRepository
     */
    public function __construct(CommentRepository $commentRepository, ConfigurationService $configurationService)
    {
        $this->commentRepository = $commentRepository;
        $this->configurationService = $configurationService;
    }

    /**
     * @Route("/comment/post/{post}/{parent}", name="comment_create")
     * @ParamConverter("post", class="App\Entity\Post")
     * @ParamConverter("parent", class="App\Entity\Comment")
     *
     * @param Post         $post
     * @param Comment|null $parent
     * @param Request      $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \App\Exception\WrongEntityClassException
     */
    public function create(Request $request, Post $post, Comment $parent = null)
    {
        $configuration = $this->configurationService->getConfiguration();
        $reCaptcha = new ReCaptcha($configuration['google_recaptcha_secret_key']);
        $reCaptchaResponse = $reCaptcha->verify(
            $request->request->get('g-recaptcha-response'),
            $request->getClientIp()
        );
        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $reCaptchaResponse->isSuccess()) {
            /** @var Comment $comment */
            $comment = $form->getData();
            $comment->setPost($post);
            $post->getComments()->add($comment);
            if ($parent instanceof Comment) {
                $comment->setParent($parent);
            }
            $this->commentRepository->add($comment);
            $response = $this->render('comment/ajax_comment.html.twig', [
                'comment' => $comment,
                'post' => $post,
            ]);
        } else {
            $response = new Response();
            $response->setStatusCode(500);
        }

        return $response;
    }

    /**
     * @Route("/comment/remove/{comment}/{post}", name="comment_remove")
     *
     * @ParamConverter("comment", class="App\Entity\Comment")
     * @ParamConverter("post", class="App\Entity\Post")
     *
     * @param Comment                       $comment
     * @param Post                          $post
     *
     * @param AuthorizationCheckerInterface $authChecker
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \App\Exception\WrongEntityClassException
     */
    public function remove(Comment $comment, Post $post, AuthorizationCheckerInterface $authChecker)
    {
        if (!$authChecker->isGranted('ROLE_ADMIN')) {
            $response = new Response('You are not authorized to do such an action!');
            $response->setStatusCode(403);

            return $response;
        }
        $this->addFlash(
            'info',
            'Successfully removed comment!'
        );
        $this->commentRepository->remove($comment);

        return $this->redirectToRoute('post_show', ['slug' => $post->getSlug()]);
    }
}
