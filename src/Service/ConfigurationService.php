<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\ConfigurationNotFoundException;
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
     * @param string $name
     *
     * @return mixed
     *
     * @throws ConfigurationNotFoundException
     */
    public function getConfigurationEntry(string $name)
    {
        $configuration = $this->getConfiguration();
        if (!array_key_exists($name, $configuration)) {
            throw new ConfigurationNotFoundException("Configuration entry with name $name not found!", 1519060809);
        }

        return $configuration[$name];
    }

    /**
     * @param array $newConfiguration
     */
    public function updateConfiguration(array $newConfiguration)
    {
        $configuration = $this->getConfiguration();
        foreach ($newConfiguration as $key => $value) {
            if (!key_exists($key, $configuration)) {
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
