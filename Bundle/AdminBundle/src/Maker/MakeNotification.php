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

class MakeNotification extends AbstractMaker
{
    private const NAME = 'make:admin:notification';
    private const DESCRIPTION = 'Crate admin notification provider';

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

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $entity = $generator->createClassNameDetails('AdminNotification', 'Entity\\');
        $repository = $generator->createClassNameDetails($entity->getShortName(), 'Repository\\', 'Repository');
        $provider = $generator->createClassNameDetails('AdminNotification', 'Notification\\', 'Provider');

        $vars = [
            'entity' => $entity,
            'repository' => $repository,
            'provider' => $provider
        ];

        $generator->generateClass(
            $entity->getFullName(),
            $this->helper->template('notification/NotificationEntity.tpl.php'),
            $vars
        );
        $generator->generateClass(
            $repository->getFullName(),
            $this->helper->template('EntityRepository.tpl.php'),
            $vars
        );
        $generator->generateClass(
            $provider->getFullName(),
            $this->helper->template('notification/NotificationProvider.tpl.php'),
            $vars
        );

        $generator->writeChanges();
        $this->writeSuccessMessage($io);
    }
}
