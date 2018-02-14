<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ConfigurationService.
 */
class ConfigurationService
{
    /**
     * @var null|ContainerInterface
     */
    private $container = null;

    /**
     * ConfigurationService constructor.
     *
     * @param ContainerInterface|null $container
     */
    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function getConfiguration(): array
    {
        return Yaml::parseFile($this->getConfigurationFilePath());
    }

    /**
     * @param array $newConfiguration
     */
    public function updateConfiguration(array $newConfiguration)
    {
        $configuration = $this->getConfiguration();
        foreach ($newConfiguration as $key => $value) {
            if(!key_exists($key, $configuration)) {
                continue;
            }
            $configuration[$key] = $value;
        }
        file_put_contents($this->getConfigurationFilePath(), Yaml::dump($configuration));
    }

    /**
     * @return string
     */
    private function getConfigurationFilePath(): string
    {
        return $this->container->get('kernel')->getRootDir().'/../config/blog.yaml';
    }
}
