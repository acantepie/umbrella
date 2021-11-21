<?php

namespace Umbrella\AdminBundle\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Util\YamlSourceManipulator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Umbrella\AdminBundle\Maker\Utils\MakeHelper;

class MakeHome extends AbstractMaker
{
    private const UMBRELLA_ADMIN_CONFIG_KEY = 'umbrella_admin';

    private const NAME = 'make:admin:home';
    private const DESCRIPTION = 'Create admin home';

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
        $controllerClass = $this->helper->askControllerClass($io, 'Admin\\HomeController');

        $controller = $generator->createClassNameDetails($controllerClass, 'Controller\\', 'Controller');
        $menu = $generator->createClassNameDetails('AdminMenu', 'Menu\\', 'Menu');

        $vars = [
            'controller' => $controller,
            'route' => $this->helper->getRouteConfig($controller, '/admin'),
            'menu' => $menu,
            'template' => Str::asFilePath($controller->getRelativeNameWithoutSuffix()) . '/index.html.twig'
        ];

        $generator->generateClass(
            $controller->getFullName(),
            $this->helper->template('HomeController.tpl.php'),
            $vars
        );
        $generator->generateClass(
            $menu->getFullName(),
            $this->helper->template('Menu.tpl.php'),
            $vars
        );
        $generator->generateTemplate(
            $vars['template'],
            $this->helper->template('template_home.tpl.php'),
            $vars
        );

        $this->updateMenuConfig($menu->getFullName());

        $generator->writeChanges();
        $this->writeSuccessMessage($io);
    }

    private function updateMenuConfig(string $menuClass): void
    {
        $configPath = 'config/packages/umbrella_admin.yaml';

        $configContent = $this->helper->fileExists($configPath)
            ? $this->helper->getFileContents($configPath)
            : self::UMBRELLA_ADMIN_CONFIG_KEY . ':';

        $manipulator = new YamlSourceManipulator($configContent);
        $data = $manipulator->getData();
        $data[self::UMBRELLA_ADMIN_CONFIG_KEY]['app_name'] = 'Admin';
        $data[self::UMBRELLA_ADMIN_CONFIG_KEY]['menu'] = $menuClass;
        $manipulator->setData($data);

        $this->helper->writeFileContents($configPath, $manipulator->getContents());
    }
}
