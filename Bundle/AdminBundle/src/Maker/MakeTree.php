<?php

namespace Umbrella\AdminBundle\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class MakeTree extends MakeTable
{
    public static function getCommandName(): string
    {
        return 'make:tree';
    }

    public static function getCommandDescription(): string
    {
        return 'Create an admin tree view';
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $controllerNamespace = $this->askControllerNamespace($io);
        $entityNamespace = $this->askEntityNamespace($io);

        $editOnModal = $io->askQuestion(new ConfirmationQuestion('Edit entity on a modal', true));
        $createTemplate = $io->askQuestion(new ConfirmationQuestion('Create twig template', true));

        // class details
        $entity = $generator->createClassNameDetails($entityNamespace . $input->getArgument('entity_name'), 'Entity\\');
        $repository = $generator->createClassNameDetails($entityNamespace . $entity->getShortName(), 'Repository\\', 'Repository');
        $form = $generator->createClassNameDetails($entityNamespace . $entity->getShortName(), 'Form\\', 'Type');
        $table = $generator->createClassNameDetails($entityNamespace . $entity->getShortName(), 'DataTable\\', 'TableType', );
        $controller = $generator->createClassNameDetails($controllerNamespace . $entity->getShortName(), 'Controller\\', 'Controller');

        $routePath = Str::asRoutePath($controller->getRelativeNameWithoutSuffix());
        $routeName = $this->asRouteName($controller);

        if ($createTemplate) {
            $indexTemplateName = Str::asFilePath($controller->getRelativeNameWithoutSuffix()) . '/index.html.twig';
            $editTemplateName = Str::asFilePath($controller->getRelativeNameWithoutSuffix()) . '/edit.html.twig';
        } else {
            $indexTemplateName = '@UmbrellaAdmin/DataTable/index.html.twig';

            if ($editOnModal) {
                $editTemplateName = '@UmbrellaAdmin/edit_modal.html.twig';
            } else {
                $editTemplateName = '@UmbrellaAdmin/edit.html.twig';
            }
        }

        // add operation
        $generator->generateClass($entity->getFullName(), $this->baseTemplateName . 'NestedEntity.tpl.php', [
            'repository' => $repository
        ]);
        $generator->generateClass($repository->getFullName(), $this->baseTemplateName . 'NestedRepository.tpl.php', [
            'entity' => $entity
        ]);
        $generator->generateClass($form->getFullName(), $this->baseTemplateName . 'NestedFormType.tpl.php', [
            'entity' => $entity
        ]);

        $generator->generateClass($table->getFullName(), $this->baseTemplateName . 'NestedTableType.tpl.php', [
            'route_name' => $routeName,
            'entity' => $entity,
            'edit_on_modal' => $editOnModal
        ]);

        $generator->generateClass($controller->getFullName(), $this->baseTemplateName . 'Controller.tpl.php', [
            'route_name' => $routeName,
            'route_path' => $routePath,
            'entity' => $entity,
            'repository' => $repository,
            'table' => $table,
            'form' => $form,
            'index_template_name' => $indexTemplateName,
            'edit_template_name' => $editTemplateName,
            'edit_on_modal' => $editOnModal,
            'tree_view' => true
        ]);

        if ($createTemplate) {
            $generator->generateTemplate($editTemplateName, $this->baseTemplateName . 'template_edit.tpl.php', [
                'edit_on_modal' => $editOnModal
            ]);
            $generator->generateTemplate($indexTemplateName, $this->baseTemplateName . 'template_index.tpl.php');
        }

        $generator->writeChanges();
        $this->writeSuccessMessage($io);

        $io->text(sprintf('Next: Check your new CRUD by going to <fg=yellow>/admin%s/</>', $routePath));
    }
}
