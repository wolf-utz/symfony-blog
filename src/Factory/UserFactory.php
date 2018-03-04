<?php
/**
 * Copyright (c) 2018 Wolf Utz <wpu@hotmail.de>.
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

namespace App\Factory;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFactory.
 */
class UserFactory
{
    /**
     * @var null|UserPasswordEncoderInterface
     */
    private $encoder = null;

    /**
     * UserFactory constructor.
     *
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Builds a new user object.
     *
     * @param string $username
     * @param string $password
     * @param string $email
     *
     * @return User
     */
    public function build(string $username, string $password, string $email)
    {
        $user = new User();
        $user->setHidden(false);
        $user->setUsername($username);
        $user->setPlainPassword($password);
        $user->setEmail($email);
        $user->setPassword($this->encoder->encodePassword($user, $password));
        $user->eraseCredentials();

        return $user;
    }

    /**
     * @param mixed $formData
     *
     * @return User
     */
    public function buildByFormData($formData)
    {
        /** @var User $user */
        $user = $formData;
        $user->setPassword($this->encoder->encodePassword($user, $user->getPlainPassword()));
        $user->eraseCredentials();

        return $user;
    }
}
