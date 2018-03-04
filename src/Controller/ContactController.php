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

use App\Entity\ContactRequest;
use App\Form\ContactRequestType;
use App\Repository\ContactRequestRepository;
use App\Service\ConfigurationService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use ReCaptcha\ReCaptcha;

/**
 * Class ContactController.
 */
class ContactController extends Controller
{
    /**
     * @var null|ContactRequestRepository
     */
    private $contactRequestRepository = null;

    /**
     * @var null|ConfigurationService
     */
    private $configurationService = null;

    /**
     * @var null|\Swift_Mailer
     */
    private $mailer = null;

    /**
     * ContactController constructor.
     *
     * @param ContactRequestRepository $contactRequestRepository
     * @param ConfigurationService     $configurationService
     */
    public function __construct(
        ContactRequestRepository $contactRequestRepository,
        ConfigurationService $configurationService,
        \Swift_Mailer $mailer
    ) {
        $this->contactRequestRepository = $contactRequestRepository;
        $this->configurationService = $configurationService;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/contact", name="contact_new")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $form = $this->createForm(ContactRequestType::class, new ContactRequest(), [
            'action' => $this->generateUrl('contact_create'),
        ]);
        $form->handleRequest($request);

        return $this->render('contact/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/contact/create", name="contact_create")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \App\Exception\WrongEntityClassException
     */
    public function create(Request $request)
    {
        $env = $this->container->get( 'kernel' )->getEnvironment();
        $configuration = $this->configurationService->getConfiguration();
        $reCaptcha = new ReCaptcha($configuration['google_recaptcha_secret_key']);
        $reCaptchaResponse = $reCaptcha->verify(
            $request->request->get('g-recaptcha-response'),
            $request->getClientIp()
        );
        $form = $this->createForm(ContactRequestType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() &&
            ($reCaptchaResponse->isSuccess() || $env === "test" || $env === "dev")
        ) {
            /** @var ContactRequest $contactRequest */
            $contactRequest = $form->getData();
            // TODO: Take a look at the progress...
            $message = (new \Swift_Message('Hello Email'))
                ->setFrom($contactRequest->getEmail())
                ->setTo($configuration['email'])
                ->setBody(
                    $this->renderView('emails/contact/new.html.twig', ["contactRequest" => $form->getData()]),
                    'text/html'
                );
            $this->mailer->send($message);
            $this->contactRequestRepository->add($form->getData());
            $this->addFlash(
                'success',
                'Successfully sent request!'.
                ($env === "test" || $env === "dev" ? " (ignoring CAPTCHA while in env: $env)":'')
            );
        } elseif (!$reCaptchaResponse->isSuccess()) {
            $this->addFlash(
                'danger',
                'The captcha validation has failed!'
            );
        }

        return $this->redirectToRoute('contact_new', ['request' => $request]);
    }
}
