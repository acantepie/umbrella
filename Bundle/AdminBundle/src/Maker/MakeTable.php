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

class MakeTable extends AbstractMaker
{
    private const NAME = 'make:admin:table';
    private const DESCRIPTION = 'Generate a CRUD with DataTable view';

    public function __construct(private MakeHelper $helper)
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
        $entitySearchable = $this->helper->askEntitySearchable($io);
        $controllerClass = $this->helper->askControllerClass($io, $this->helper->getDefaultControllerClassFromEntityClass($entityClass));
        $editViewType = $this->helper->askEditViewTypeClass($io);

        // class details
        $entity = $generator->createClassNameDetails($entityClass, 'Entity\\');
        $repository = $generator->createClassNameDetails($entityClass, 'Repository\\', 'Repository');
        $form = $generator->createClassNameDetails($entityClass, 'Form\\', 'Type');
        $table = $generator->createClassNameDetails($entityClass, 'DataTable\\', 'TableType');
        $controller = $generator->createClassNameDetails($controllerClass, 'Controller\\', 'Controller');

        $vars = [
            'entity' => $entity,
            'entity_searchable' => $entitySearchable,
            'repository' => $repository,
            'form' => $form,
            'table' => $table,
            'tree_table' => false,
            'controller' => $controller,
            'route' => $this->helper->getRouteConfig($controller),
            'index_template' => '@UmbrellaAdmin/DataTable/index.html.twig',
            'edit_view_type' => $editViewType,
            'edit_template' => Str::asFilePath($controller->getRelativeNameWithoutSuffix()) . '/edit.html.twig'
        ];

        // add operation
        if (!class_exists($entity->getFullName())) {
            $generator->generateClass(
                $entity->getFullName(),
                $this->helper->template('Entity.tpl.php'),
                $vars
            );
        }
        if (!class_exists($repository->getFullName())) {
            $generator->generateClass(
                $repository->getFullName(),
                $this->helper->template('EntityRepository.tpl.php'),
                $vars
            );
        }
        if (!class_exists($form->getFullName())) {
            $generator->generateClass(
                $form->getFullName(),
                $this->helper->template('FormType.tpl.php'),
                $vars
            );
        }
        $generator->generateClass(
            $table->getFullName(),
            $this->helper->template('TableType.tpl.php'),
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
