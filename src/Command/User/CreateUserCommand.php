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

namespace App\Command\User;

use App\Factory\UserFactory;
use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class CreateUserCommand.
 */
class CreateUserCommand extends Command
{
    /**
     * @var UserFactory|null
     */
    private $userFactory = null;

    /**
     * @var UserRepository|null
     */
    private $userRepository = null;

    /**
     * CreateUserCommand constructor.
     *
     * @param UserFactory    $userFactory
     * @param UserRepository $userRepository
     */
    public function __construct(UserFactory $userFactory, UserRepository $userRepository)
    {
        parent::__construct();
        $this->userFactory = $userFactory;
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
        $user = $this->userFactory->build(
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
}
