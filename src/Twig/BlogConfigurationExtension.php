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

namespace App\Twig;

use App\Service\ConfigurationService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class BlogConfigurationExtension.
 */
class BlogConfigurationExtension extends AbstractExtension
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
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return array(
            new TwigFunction('configuration', array($this, 'getConfiguration')),
        );
    }

    /**
     * @return array
     */
    public function getConfiguration() : array
    {
        return $this->configurationService->getConfiguration();
    }
}