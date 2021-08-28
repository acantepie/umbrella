<?php

namespace Umbrella\AdminBundle\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Doctrine\DoctrineHelper;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class MakeTable extends AbstractMaker
{
    protected DoctrineHelper $doctrineHelper;
    protected string $baseTemplateName = __DIR__ . '/../../skeleton/';

    public function __construct(DoctrineHelper $doctrineHelper)
    {
        $this->doctrineHelper = $doctrineHelper;
    }

    public static function getCommandName(): string
    {
        return 'make:table';
    }

    public static function getCommandDescription(): string
    {
        return 'Create an admin table view';
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
        $controllerNamespace = $this->askControllerNamespace($io);
        $entityNamespace = $this->askEntityNamespace($io);

        $entitySearchable = $io->askQuestion(new ConfirmationQuestion('Add the ability to search your entity with text', true));
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

        if (!class_exists($entity->getFullName())) {
            $generator->generateClass($entity->getFullName(), $this->baseTemplateName . 'Entity.tpl.php', [
                'entity_searchable' => $entitySearchable,
                'repository' => $repository
            ]);
        }

        if (!class_exists($repository->getFullName())) {
            $generator->generateClass($repository->getFullName(), $this->baseTemplateName . 'EntityRepository.tpl.php', [
                'entity' => $entity
            ]);
        }

        if (!class_exists($form->getFullName())) {
            $generator->generateClass($form->getFullName(), $this->baseTemplateName . 'FormType.tpl.php', [
                'entity' => $entity
            ]);
        }

        $generator->generateClass($table->getFullName(), $this->baseTemplateName . 'TableType.tpl.php', [
            'route_name' => $routeName,
            'entity' => $entity,
            'entity_searchable' => $entitySearchable,
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
            'tree_view' => false
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

    protected function createEntityClassQuestion(string $questionText): Question
    {
        $question = new Question($questionText);
        $question->setValidator([Validator::class, 'notBlank']);
        $question->setAutocompleterValues($this->doctrineHelper->getEntitiesForAutocomplete());

        return $question;
    }

    protected function askControllerNamespace(ConsoleStyle $io): ?string
    {
        $question = new ChoiceQuestion('Sub namespace of your your Controller', [
            'Admin\\', 'none', 'other'
        ], 0);

        $answer = $io->askQuestion($question);

        if ('none' === $answer) {
            return null;
        }

        if ('other' === $answer) {
            $question = new Question('Specify - e.g. <fg=yellow>Admin\Foo</>');
            $question->setValidator([Validator::class, 'notBlank']);
            $answer = $io->askQuestion($question);
            return rtrim($answer, '\\') . '\\';
        }

        return $answer;
    }

    protected function askEntityNamespace(ConsoleStyle $io): ?string
    {
        $question = new ChoiceQuestion('Sub namespace of your your Entity', [
            'none', 'other'
        ], 0);

        $answer = $io->askQuestion($question);

        if ('other' === $answer) {
            $question = new Question('Specify - e.g. <fg=yellow>Admin\Foo</>');
            $question->setValidator([Validator::class, 'notBlank']);
            $answer = $io->askQuestion($question);
            return rtrim($answer, '\\') . '\\';
        }

        return null;
    }

    // Hack : symfony fail to generate route name
    protected function asRouteName(ClassNameDetails $controller): string
    {
        // App\Foo\Bar\Controller\BazController => App\Foo\Bar\Baz
        $s = str_replace('Controller\\' . $controller->getRelativeName(), '', $controller->getFullName()) . $controller->getRelativeNameWithoutSuffix();

        // App\Foo\Bar\Baz => App_Foo_Bar_Baz
        $s = str_replace('\\', '_', $s);

        // App\Foo\Bar\Baz => app_foo_bar_baz
        return strtolower($s);
    }
}
