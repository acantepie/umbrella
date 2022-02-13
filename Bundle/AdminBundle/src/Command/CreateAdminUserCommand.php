<?php

namespace Umbrella\AdminBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Umbrella\AdminBundle\Service\UserManagerInterface;

class CreateAdminUserCommand extends Command
{
    protected static $defaultName = 'umbrella:create:admin_user';
    protected static $defaultDescription = 'Create a new admin user.';

    private ?SymfonyStyle $io = null;

    public function __construct(private UserManagerInterface $userManager)
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $u = $this->userManager->create();

        $u->firstname = $this->io->askQuestion(new Question('Firstname'));
        $u->lastname = $this->io->askQuestion(new Question('Lastname'));
        $u->email = $this->io->askQuestion(new Question('Email (must be unique)'));
        $u->plainPassword = $this->io->askQuestion(new Question('Password'));

        $this->userManager->update($u);

        $this->io->success('User created');

        return self::SUCCESS;
    }
}
