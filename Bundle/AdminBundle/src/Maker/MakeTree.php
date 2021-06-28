<?php

namespace Umbrella\AdminBundle\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Doctrine\DoctrineHelper;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class MakeTree extends AbstractMaker
{
    private DoctrineHelper $doctrineHelper;
    private string $baseTemplateName = __DIR__ . '/../../skeleton/';

    public function __construct(DoctrineHelper $doctrineHelper)
    {
        $this->doctrineHelper = $doctrineHelper;
    }

    public static function getCommandName(): string
    {
        return 'make:tree';
    }

    public static function getCommandDescription(): string
    {
        return 'Create an admin tree view';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
        $command
            ->addArgument('entity_name', InputArgument::OPTIONAL, sprintf('Class name of the entity to create (e.g. <fg=yellow>%s</>)', Str::asClassName(Str::getRandomTerm())));
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {
    }

    public function interact(InputInterface $input, ConsoleStyle $io, Command $command)
    {
        if ($input->getArgument('entity_name')) {
            return;
        }

        $argument = $command->getDefinition()->getArgument('entity_name');
        $question = $this->createEntityClassQuestion($argument->getDescription());
        $value = $io->askQuestion($question);

        $input->setArgument('entity_name', $value);
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $editOnModal = $io->askQuestion(new ConfirmationQuestion('Edit entity on a modal', true));
        $createTemplate = $io->askQuestion(new ConfirmationQuestion('Create twig template', true));

        // class details
        $entity = $generator->createClassNameDetails($input->getArgument('entity_name'), 'Entity\\');
        $repository = $generator->createClassNameDetails($entity->getShortName(), 'Repository\\', 'Repository');
        $form = $generator->createClassNameDetails($entity->getShortName(), 'Form\\', 'Type');
        $table = $generator->createClassNameDetails($entity->getShortName(), 'DataTable\\', 'TableType', );
        $controller = $generator->createClassNameDetails($entity->getShortName(), 'Controller\\Admin\\', 'Controller');

        $routePath = Str::asRoutePath($controller->getRelativeNameWithoutSuffix());
        $routeName = 'app_admin_' . Str::asRouteName($controller->getRelativeNameWithoutSuffix());

        if ($createTemplate) {
            $indexTemplateName = 'admin/' . Str::asFilePath($controller->getRelativeNameWithoutSuffix()) . '/index.html.twig';
            $editTemplateName = 'admin/' . Str::asFilePath($controller->getRelativeNameWithoutSuffix()) . '/edit.html.twig';
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

    private function createEntityClassQuestion(string $questionText): Question
    {
        $question = new Question($questionText);
        $question->setValidator([Validator::class, 'notBlank']);
        $question->setAutocompleterValues($this->doctrineHelper->getEntitiesForAutocomplete());

        return $question;
    }
}
