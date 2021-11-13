<?php

namespace Umbrella\AdminBundle\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Umbrella\AdminBundle\Maker\Utils\MakeHelper;

class MakeTree extends AbstractMaker
{
    private const NAME = 'make:tree';
    private const DESCRIPTION = 'Creates CRUD with Tree DataTable view';

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
        $controllerClass = $this->helper->askControllerClass($io, $entityClass);
        $editViewType = $this->helper->askEditViewTypeClass($io);

        // class details
        $entity = $generator->createClassNameDetails($entityClass, 'Entity\\');
        $repository = $generator->createClassNameDetails($entityClass, 'Repository\\', 'Repository');
        $form = $generator->createClassNameDetails($entityClass, 'Form\\', 'Type');
        $table = $generator->createClassNameDetails($entityClass, 'DataTable\\', 'TableType');
        $controller = $generator->createClassNameDetails($controllerClass, 'Controller\\', 'Controller');

        $vars = [
            'entity' => $entity,
            'entity_searchable' => false,
            'repository' => $repository,
            'form' => $form,
            'table' => $table,
            'tree_table' => true,
            'controller' => $controller,
            'route' => $this->helper->getRouteConfig($controller),
            'index_template' => '@UmbrellaAdmin/DataTable/index.html.twig',
            'edit_view_type' => $editViewType,
            'edit_template' => Str::asFilePath($controller->getRelativeNameWithoutSuffix()) . '/edit.html.twig'
        ];

        // add operation
        $generator->generateClass(
            $entity->getFullName(),
            $this->helper->template('NestedEntity.tpl.php'),
            $vars
        );
        $generator->generateClass(
            $repository->getFullName(),
            $this->helper->template('NestedRepository.tpl.php'),
            $vars
        );
        $generator->generateClass(
            $form->getFullName(),
            $this->helper->template('NestedFormType.tpl.php'),
            $vars
        );
        $generator->generateClass(
            $table->getFullName(),
            $this->helper->template('NestedTableType.tpl.php'),
            $vars
        );
        $generator->generateClass(
            $controller->getFullName(),
            $this->helper->template('Controller.tpl.php'),
            $vars
        );
        $templateName = MakeHelper::VIEW_MODAL === $editViewType ? 'template_edit_modal.tpl.php' : 'template_edit.tpl.php';
        $generator->generateTemplate(
            $vars['edit_template'],
            $this->helper->template($templateName),
            $vars
        );

        $generator->writeChanges();
        $this->writeSuccessMessage($io);
    }
}
