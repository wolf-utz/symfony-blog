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

use App\Service\ConfigurationService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ConfigurationController.
 */
class ConfigurationController extends Controller
{
    /**
     * @var null|ConfigurationService
     */
    private $configurationService = null;

    /**
     * ConfigurationController constructor.
     *
     * @param ConfigurationService $configurationService
     */
    public function __construct(ConfigurationService $configurationService)
    {
        $this->configurationService = $configurationService;
    }

    /**
     * @Route("/backend/configuration", name="backend_configuration_index")
     */
    public function index()
    {
        return $this->render('backend/configuration/index.html.twig', [
            'configuration' => $this->configurationService->getConfiguration(),
        ]);
    }

    /**
     * @Route("/backend/configuration/write", name="backend_configuration_write")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function write(Request $request)
    {
        $this->configurationService->updateConfiguration($request->request->all());
        $this->addFlash('success', 'Successfully updated the configuration!');

        return $this->redirectToRoute('backend_configuration_index');
    }
}
