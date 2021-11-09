<?php

namespace Umbrella\AdminBundle\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

class MakeNotification extends AbstractMaker
{
    private string $baseTemplateName = __DIR__ . '/../../skeleton/';

    public static function getCommandName(): string
    {
        return 'make:notification';
    }

    public static function getCommandDescription(): string
    {
        return 'Create notification';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        // class details
        $entity = $generator->createClassNameDetails('AdminNotification', 'Entity\\');
        $repository = $generator->createClassNameDetails($entity->getShortName(), 'Repository\\', 'Repository');
        $provider = $generator->createClassNameDetails('AdminNotification', 'Notification\\', 'Provider');

        // add operation

        $generator->generateClass($entity->getFullName(), $this->baseTemplateName . 'notification/NotificationEntity.tpl.php', [
            'repository' => $repository
        ]);
        $generator->generateClass($repository->getFullName(), $this->baseTemplateName . 'EntityRepository.tpl.php', [
            'entity' => $entity
        ]);
        $generator->generateClass($provider->getFullName(), $this->baseTemplateName . 'notification/NotificationProvider.tpl.php', [
            'entity' => $entity
        ]);

        $generator->writeChanges();
        $this->writeSuccessMessage($io);
    }
}
