<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2018 Wolf Utz <utz@riconet.de>, riconet
 *      Created on: 03.03.18 21:34
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

namespace App\Command\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class CreateUserCommand.
 */
class CreateUserCommand extends Command
{
    /**
     * @var null|UserPasswordEncoderInterface
     */
    private $encoder = null;

    /**
     * @var UserRepository|null
     */
    private $userRepository = null;

    /**
     * CreateUserCommand constructor.
     *
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder, UserRepository $userRepository)
    {
        parent::__construct();
        $this->encoder = $encoder;
        $this->userRepository = $userRepository;
    }

    /**
     * Configures the command.
     */
    protected function configure()
    {
        $this
            ->setName('blog:user:create')
            ->setDescription('Creates a new user.')
            ->setHelp('This command allows you to create a user...')
            ->addArgument('username', InputArgument::REQUIRED, 'The username of the user.')
            ->addArgument('password', InputArgument::REQUIRED, 'The password of the user.')
            ->addArgument('email', InputArgument::REQUIRED, 'The unique email of the user.')
        ;
    }

    /**
     * Excecutes the command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $user = $this->buildUser(
            $input->getArgument('username'),
            $input->getArgument('password'),
            $input->getArgument('email')
        );
        try {
            $this->userRepository->add($user);
            $io->success('Successfully created a new user!');
        } catch (\Exception $e) {
            $io->error('Failed to created a new user!');
            $io->error($e->getMessage());
        }
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
    protected function buildUser(string $username, string $password, string $email)
    {
        $user = new User();
        $user->setHidden(false);
        $user->setUsername($username);
        $user->setPlainPassword($password);
        $user->setEmail($email);
        $user->setPassword($this->encoder->encodePassword($user, $password));

        return $user;
    }
}
