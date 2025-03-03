<?php

namespace Umbrella\AdminBundle\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Util\YamlSourceManipulator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Umbrella\AdminBundle\Maker\Utils\MakeHelper;

class MakeAdminUser extends AbstractMaker
{
    private const NAME = 'make:admin:user';
    private const DESCRIPTION = 'Generate an admin user entity';

    public function __construct(private readonly MakeHelper $helper)
    {
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

    public function interact(InputInterface $input, ConsoleStyle $io, Command $command): void
    {
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $entityClass = $this->helper->askEntityClass($io, 'User');

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
        $this->updateUserConfig($io, $entity->getFullName());
        $this->updateSecurityConfig($io, $entity->getFullName());
        $this->writeSuccessMessage($io);

        $io->text('Next Steps:');
        $io->text(' - Configure firewall on <info>security.yaml</info>');
        $io->text(' - Register security and user routes on <info>routes.yaml</info>');
        $io->text(' - Add entry on admin menu');

        $io->writeln('');
        $io->writeln('Read more about it on <href=https://github.com/acantepie/umbrella/blob/master/docs/manage_user_with_doctrine.md>Documentation</>');
        $io->writeln('');
    }

    private function updateUserConfig(SymfonyStyle $io, string $userClass): void
    {
        $configPath = 'config/packages/umbrella_admin.yaml';

        $configContent = $this->helper->fileExists($configPath)
            ? $this->helper->getFileContents($configPath)
            : 'umbrella_admin:';

        $manipulator = new YamlSourceManipulator($configContent);
        $data = $manipulator->getData();
        $data['umbrella_admin']['user']['class'] = $userClass;

        $manipulator->setData($data);
        $this->helper->writeFileContents($configPath, $manipulator->getContents());

        $io->writeln(\sprintf(' <fg=yellow>updated</>: %s', $configPath));
    }

    private function updateSecurityConfig(SymfonyStyle $io, string $userClass): void
    {
        $configPath = 'config/packages/security.yaml';

        $configContent = $this->helper->fileExists($configPath)
            ? $this->helper->getFileContents($configPath)
            : 'security:';

        $manipulator = new YamlSourceManipulator($configContent);
        $data = $manipulator->getData();

        $data['security']['enable_authenticator_manager'] = true;

        // password hashers
        $data['security']['password_hashers'][$userClass] = 'auto';

        // provider
        $data['security']['providers']['admin_entity_provider']['entity'] = [
            'class' => $userClass,
            'property' => 'email'
        ];

        $manipulator->setData($data);
        $this->helper->writeFileContents($configPath, $manipulator->getContents());

        $io->writeln(\sprintf(' <fg=yellow>updated</>: %s', $configPath));
    }
}
