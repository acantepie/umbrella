<?php

namespace Umbrella\AdminBundle\Maker;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Umbrella\AdminBundle\Maker\Console\ConsoleStyle;
use Umbrella\AdminBundle\Maker\Console\InputConfiguration;
use Umbrella\AdminBundle\Maker\Generator\Generator;

/**
 * Class MakeNotification
 */
class MakeNotification extends AbstractMaker
{
    private EntityManagerInterface $em;

    /**
     * MakeNotification constructor.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getCommandName(): string
    {
        return 'umbrella:make:notification';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
        $command->setDescription('Setup notification on project');
        $command->addOption('force', 'f', InputOption::VALUE_NONE, 'Overwrite existing files');
        $command->addOption('update-schema', 'u', InputOption::VALUE_NONE, 'Update doctrine schema');
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $force = $input->getOption('force');
        $updateSchema = $input->getOption('update-schema');

        $entityNotificationMeta = $generator->createMetaClass('Notification', 'Entity');
        $notificationProviderMeta = $generator->createMetaClass('NotificationProvider', 'Notification');

        $params = [
            'entity_notification' => $entityNotificationMeta,
            'notification_provider' => $notificationProviderMeta
        ];

        $generator->generateClass($entityNotificationMeta->getFilePath(), 'notification/Notification.tpl.php', $params);
        $generator->generateClass($notificationProviderMeta->getFilePath(), 'notification/NotificationProvider.tpl.php', $params);

        $generator->writeChanges($force);

        if ($updateSchema) {
            $this->updateSchema($this->em, $io);
        }

        $io->doneSuccess();
    }
}
