<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;

/**
 * Class AbstractEntity.
 *
 * @MappedSuperclass
 */
abstract class AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    protected $hidden = true;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    protected $created = null;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    protected $lastUpdated = null;

    /**
     * Post constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    protected function init()
    {
        $this->setLastUpdated();
        $this->setCreated();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * @param bool $hidden
     */
    public function setHidden(bool $hidden): void
    {
        $this->hidden = $hidden;
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    public function setCreated(): void
    {
        $this->created = new \DateTime('now');
    }

    /**
     * @return \DateTime
     */
    public function getLastUpdated(): \DateTime
    {
        return $this->lastUpdated;
    }

    public function setLastUpdated(): void
    {
        $this->lastUpdated = new \DateTime('now');
    }
}
