<?php

namespace Umbrella\AdminBundle\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Umbrella\AdminBundle\Maker\Utils\MakeHelper;

class MakeAdminUser extends AbstractMaker
{
    private const NAME = 'make:admin_user';
    private const DESCRIPTION = 'Create admin user entity';

    private MakeHelper $helper;

    public function __construct(MakeHelper $helper)
    {
        $this->helper = $helper;
    }

    public static function getCommandName(): string
    {
        return self::NAME;
    }

    public static function getCommandDescription(): string
    {
        return self::DESCRIPTION;
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {
    }

    public function interact(InputInterface $input, ConsoleStyle $io, Command $command)
    {
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $entityClass = $this->helper->askEntityClass($io);

        $entity = $generator->createClassNameDetails($entityClass, 'Entity\\');
        $repository = $generator->createClassNameDetails($entityClass, 'Repository\\', 'Repository');

        $vars = [
            'entity' => $entity,
            'repository' => $repository
        ];

        $generator->generateClass(
            $entity->getFullName(),
            $this->helper->template('AdminUser.tpl.php'),
            $vars
        );
        $generator->generateClass(
            $repository->getFullName(),
            $this->helper->template('EntityRepository.tpl.php'),
            $vars
        );

        $generator->writeChanges();
        $this->writeSuccessMessage($io);
    }
}
