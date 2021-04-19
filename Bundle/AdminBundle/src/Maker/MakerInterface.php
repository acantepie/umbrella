<?php

namespace Umbrella\AdminBundle\Maker;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Umbrella\AdminBundle\Maker\Console\ConsoleStyle;
use Umbrella\AdminBundle\Maker\Console\InputConfiguration;
use Umbrella\AdminBundle\Maker\Generator\Generator;

/**
 * Interface MakerInterface
 */
interface MakerInterface
{
    /**
     * Return the command name for your maker (e.g. make:report).
     */
    public static function getCommandName(): string;

    /**
     * Configure the command: set description, input arguments, options, etc.
     *
     * By default, all arguments will be asked interactively. If you want
     * to avoid that, use the $inputConfig->setArgumentAsNonInteractive() method.
     */
    public function configureCommand(Command $command, InputConfiguration $inputConfig);

    /**
     * If necessary, you can use this method to interactively ask the user for input.
     */
    public function interact(InputInterface $input, ConsoleStyle $io, Command $command);

    /**
     * Called after normal code generation: allows you to do anything.
     */
    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator);
}
