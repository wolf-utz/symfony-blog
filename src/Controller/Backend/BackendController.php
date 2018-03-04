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

use App\Module\Module;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class BackendController.
 */
class BackendController extends Controller
{
    /**
     * @Route("/backend", name="backend_index")
     *
     * @param AuthorizationCheckerInterface $authChecker
     *
     * @return Response
     */
    public function indexAction(AuthorizationCheckerInterface $authChecker)
    {
        if (!$authChecker->isGranted('ROLE_ADMIN')) {
            $response = new Response('You are not authorized to visit this area!');
            $response->setStatusCode(403);

            return $response;
        }
        $modules = [
            new Module('Posts', 'Manage your posts here.', 'pencil-square-o', 'backend_post_list'),
            new Module('Comments', 'Manage the comments', 'comment', 'backend_comment_list'),
            new Module('Tags', 'Manage your tags here', 'tag', 'backend_tag_list'),
            new Module('Users', 'Manage your users here', 'users', 'backend_user_list'),
//            new Module('Statistics', 'Overview general statistics', 'bar-chart', 'backend_configuration_index'),
            new Module('Configurations', 'Configure the system', 'cogs', 'backend_configuration_index'),
        ];

        return $this->render('backend/index.html.twig', [
            'modules' => $modules,
            'user' => $this->getUser(),
        ]);
    }
}
