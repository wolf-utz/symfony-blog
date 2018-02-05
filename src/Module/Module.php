<?php

declare(strict_types=1);

namespace App\Module;

/**
 * Class Module.
 */
class Module
{
    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var string
     */
    protected $icon = '';

    /**
     * @var string
     */
    protected $route = '';

    /**
     * Module constructor.
     *
     * @param string $title
     * @param string $description
     * @param string $icon
     * @param string $route
     */
    public function __construct(string $title, string $description, string $icon, string $route)
    {
        $this->title = $title;
        $this->description = $description;
        $this->icon = $icon;
        $this->route = $route;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     */
    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @param string $route
     */
    public function setRoute(string $route): void
    {
        $this->route = $route;
    }
}
