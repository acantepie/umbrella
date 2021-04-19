<?php

namespace Umbrella\AdminBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Umbrella\AdminBundle\Maker\Console\ConsoleStyle;
use Umbrella\AdminBundle\Maker\Console\InputConfiguration;
use Umbrella\AdminBundle\Maker\Generator\Generator;
use Umbrella\AdminBundle\Maker\MakerInterface;
use Umbrella\AdminBundle\Maker\Utils\MakerValidator;

class MakerCommand extends Command
{
    protected MakerInterface $maker;

    protected Generator $generator;

    protected ?ConsoleStyle $io = null;

    protected InputConfiguration $inputConfig;

    /**
     * MakerCommand constructor.
     */
    public function __construct(MakerInterface $maker, Generator $generator)
    {
        $this->maker = $maker;
        $this->generator = $generator;
        $this->inputConfig = new InputConfiguration();
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->maker->configureCommand($this, $this->inputConfig);
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new ConsoleStyle($input, $output);
        $this->generator->setIO($this->io);
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->getDefinition()->getArguments() as $argument) {
            if ($input->getArgument($argument->getName())) {
                continue;
            }

            if (\in_array($argument->getName(), $this->inputConfig->getNonInteractiveArguments(), true)) {
                continue;
            }

            $value = $this->io->ask($argument->getDescription(), $argument->getDefault(), [MakerValidator::class, 'notBlank']);
            $input->setArgument($argument->getName(), $value);
        }

        $this->maker->interact($input, $this->io, $this);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->maker->generate($input, $this->io, $this->generator);

        return self::SUCCESS;
    }
}
