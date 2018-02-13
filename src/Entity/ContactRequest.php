<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ContactRequest.
 *
 * @ORM\Entity(repositoryClass="App\Repository\ContactRequestRepository")
 */
class ContactRequest extends AbstractEntity
{
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $name = '';

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $email = '';

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $note = '';

    /**
     * @var bool
     */
    protected $hidden = false;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }
}
