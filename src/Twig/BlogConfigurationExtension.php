<?php

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