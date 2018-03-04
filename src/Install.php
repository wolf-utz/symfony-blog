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

namespace App;

use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Install.
 */
class Install
{
    use ControllerTrait;

    /**
     * @var null|Request
     */
    private $request = null;

    /**
     * @var null|ContainerInterface
     */
    private $container = null;

    /**
     * Install constructor.
     *
     * @param Request            $request
     * @param ContainerInterface $container
     */
    public function __construct(Request $request, ContainerInterface $container)
    {
        $this->request = $request;
        $this->container = $container;
    }

    /**
     * Runs the install process.
     */
    public function run()
    {
        $this->handleRequest();
        echo $this->render('install/index.html.twig', [
            'flashes' => $this->container->get('session')->getFlashBag()->all(),
            'arguments' => $this->request->request->all(),
        ])->getContent();
    }

    /**
     * Handles the request.
     */
    private function handleRequest()
    {
        $arguments = $this->request->request->all();
        if (!isset($arguments['submit']) || !boolval($arguments['submit'])) {
            return;
        }
        if(
            $this->checkDatabaseConnection($arguments) &&
            $this->createAdminUser($arguments) &&
            $this->saveBlogData($arguments)
        ) {
            $this->markSystemAsInstalled();
            echo $this->redirectToRoute('backend_index');
            exit();
        }
    }

    /**
     * Checks the database connection.
     *
     * @param array $arguments
     *
     * @return bool
     */
    private function checkDatabaseConnection(array $arguments) : bool
    {
        try {
            $connection = new \mysqli(
                $arguments['db_host'],
                $arguments['db_user'],
                $arguments['db_password'],
                $arguments['db_name'],
                intval($arguments['db_port'])
            );
            if ($connection->connect_error) {
                $this->addFlash('danger', $connection->connect_error);

                return false;
            }
            $this->addFlash('success', 'Successfully connect to database!');
        } catch (\Exception $e) {
            $this->addFlash('danger', $e->getMessage());

            return false;
        }

        return true;
    }

    /**
     * Creates an admin user.
     *
     * @param array $arguments
     *
     * @return bool
     */
    private function createAdminUser(array $arguments) : bool
    {
        // TODO: Delegate to user creation method elsewhere.
        return true;
    }

    /**
     * Saves the blog data.
     *
     * @param array $arguments
     *
     * @return bool
     */
    private function saveBlogData(array $arguments) : bool
    {
        // TODO: Add functionality!
        return true;
    }

    /**
     * Marks the system as installed.
     */
    private function markSystemAsInstalled()
    {
        $path = __DIR__.'/../INSTALL';
        if(file_exists($path)) {
            unlink($path);
        }
    }
}
