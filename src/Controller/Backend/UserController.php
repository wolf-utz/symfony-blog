<?php
/**
 * Copyright (c) 2018 Wolf Utz <wpu@hotmail.de>.
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

use App\Entity\User;
use App\Factory\UserFactory;
use App\Form\UserType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class UserController.
 */
class UserController extends Controller
{
    /**
     * @var UserRepository|null
     */
    protected $userRepository = null;

    /**
     * @var UserFactory|null
     */
    private $userFactory = null;

    /**
     * PostController constructor.
     *
     * @param UserRepository $userRepository
     * @param UserFactory    $userFactory
     */
    public function __construct(UserRepository $userRepository, UserFactory $userFactory)
    {
        $this->userRepository = $userRepository;
        $this->userFactory = $userFactory;
    }

    /**
     * @Route("/backend/users/{currentPage}", name="backend_user_list")
     *
     * @param int $currentPage
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list($currentPage = 1)
    {
        $limit = 5;
        $users = $this->userRepository->findAllEvenHiddenPaginated($currentPage, $limit);
        $maxPages = ceil($users->count() / $limit);

        return $this->render('backend/user/list.html.twig', [
            'users' => $users,
            'currentPage' => $currentPage,
            'limit' => $limit,
            'maxPages' => $maxPages,
        ]);
    }

    /**
     * @Route("/backend/user/edit/{id}", name="backend_user_edit")
     *
     * @ParamConverter("user", class="App\Entity\User")
     *
     * @param Request $request
     * @param User    $user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, User $user)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        return $this->render('backend/user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/backend/user/update/{id}", name="backend_user_update")
     *
     * @ParamConverter("user", class="App\Entity\User")
     *
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $encoder
     * @param User                         $user
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @throws \App\Exception\WrongEntityClassException
     */
    public function update(Request $request, UserPasswordEncoderInterface $encoder, User $user)
    {
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $data */
            $data = $form->getData();
            $user->setUsername($data->getUsername());
            $user->setEmail($data->getUsername());
            $encodedPassword = $encoder->encodePassword($user, $data->getPlainPassword());
            $user->setPassword($encodedPassword);

            $this->userRepository->update($user);
            $this->addFlash(
                'success',
                'Successfully update the user with id '.$user->getId()
            );

            return $this->redirectToRoute('backend_user_list');
        } else {
            return $this->redirectToRoute('backend_user_edit', ['request' => $request, 'user' => $user]);
        }
    }

    /**
     * @Route("/backend/user/new", name="backend_user_new")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $form = $this->createForm(UserType::class, new User());
        $form->handleRequest($request);

        return $this->render('backend/user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/backend/user/create", name="backend_user_create")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @throws \App\Exception\WrongEntityClassException
     */
    public function create(Request $request)
    {
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->userFactory->buildByFormData($form->getData());
            $this->userRepository->add($user);
            $this->addFlash(
                'info',
                'Successfully created new user!'
            );

            return $this->redirectToRoute('backend_user_list');
        } else {
            return $this->redirectToRoute('backend_user_new', ['request' => $request]);
        }
    }

    /**
     * @Route("/backend/user/delete/{id}", name="backend_user_delete")
     *
     * @ParamConverter("user", class="App\Entity\User")
     *
     * @param Request $request
     * @param User    $user
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @throws \App\Exception\WrongEntityClassException
     */
    public function delete(Request $request, User $user)
    {
        $currentPage = $request->get('currentPage');
        $this->userRepository->remove($user);
        $this->addFlash(
            'info',
            'Successfully removed user with username: '.$user->getUsername().'!'
        );

        return $this->redirectToRoute('backend_user_list', ['currentPage' => $currentPage, '_fragment' => 'user-'.$user->getId()]);
    }

    /**
     * @param AuthenticationUtils           $authUtils
     * @param AuthorizationCheckerInterface $authChecker
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/backend/login", name="user_login")
     */
    public function login(AuthenticationUtils $authUtils, AuthorizationCheckerInterface $authChecker)
    {
        if ($authChecker->isGranted('ROLE_ADMIN')) {
            $response = $this->redirectToRoute('backend_index');

            return $response;
        }
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('user/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }
}
