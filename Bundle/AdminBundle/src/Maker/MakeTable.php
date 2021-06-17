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

class MakeTable extends AbstractMaker
{
    private DoctrineHelper $doctrineHelper;

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
        return 'Creates an admin table view';
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
        $entitySearchable = $io->askQuestion(new ConfirmationQuestion('Add the ability to search your entity with text', true));
        $editOnModal = $io->askQuestion(new ConfirmationQuestion('Edit entity on a modal', true));
        $createTemplate = $io->askQuestion(new ConfirmationQuestion('Create twig template', true));

        // class details

        $entityClassDetails = $generator->createClassNameDetails(
            $input->getArgument('entity_name'),
            'Entity\\'
        );
        $formClassDetails = $generator->createClassNameDetails(
            $entityClassDetails->getShortName(),
            'Form\\',
            'Type'
        );
        $tableClassDetails = $generator->createClassNameDetails(
            $entityClassDetails->getShortName(),
            'DataTable\\',
            'TableType',
        );
        $controllerClassDetails = $generator->createClassNameDetails(
            $entityClassDetails->getShortName(),
            'Controller\\Admin\\',
            'Controller'
        );

        $routePath = Str::asRoutePath($controllerClassDetails->getRelativeNameWithoutSuffix());
        $routeName = 'app_admin_' . Str::asRouteName($controllerClassDetails->getRelativeNameWithoutSuffix());

        if ($createTemplate) {
            $indexTemplateName = 'admin/' . Str::asFilePath($controllerClassDetails->getRelativeNameWithoutSuffix()) . '/index.html.twig';
            $editTemplateName = 'admin/' . Str::asFilePath($controllerClassDetails->getRelativeNameWithoutSuffix()) . '/edit.html.twig';
        } else {
            $indexTemplateName = '@UmbrellaAdmin/DataTable/index.html.twig';

            if ($editOnModal) {
                $editTemplateName = '@UmbrellaAdmin/edit_modal.html.twig';
            } else {
                $editTemplateName = '@UmbrellaAdmin/edit.html.twig';
            }
        }

        // add operation

        if (!class_exists($entityClassDetails->getFullName())) {
            $generator->generateClass(
                $entityClassDetails->getFullName(),
                __DIR__ . '/../../skeleton/Entity.tpl.php',
                [
                    'entity_searchable' => $entitySearchable
                ]
            );
        }

        if (!class_exists($formClassDetails->getFullName())) {
            $generator->generateClass(
                $formClassDetails->getFullName(),
                __DIR__ . '/../../skeleton/FormType.tpl.php',
                [
                    'entity' => $entityClassDetails
                ]
            );
        }

        if (!class_exists($tableClassDetails->getFullName())) {
            $generator->generateClass(
                $tableClassDetails->getFullName(),
                __DIR__ . '/../../skeleton/TableType.tpl.php',
                [
                    'route_name' => $routeName,
                    'entity' => $entityClassDetails,
                    'entity_searchable' => $entitySearchable,
                    'edit_on_modal' => $editOnModal
                ]
            );
        }

        $generator->generateClass(
            $controllerClassDetails->getFullName(),
            __DIR__ . '/../../skeleton/Controller.tpl.php',
            [
                'route_name' => $routeName,
                'route_path' => $routePath,
                'entity' => $entityClassDetails,
                'table' => $tableClassDetails,
                'form' => $formClassDetails,
                'index_template_name' => $indexTemplateName,
                'edit_template_name' => $editTemplateName,
                'edit_on_modal' => $editOnModal
            ]
        );

        if ($createTemplate) {
            $generator->generateTemplate(
                $editTemplateName,
                __DIR__ . '/../../skeleton/template_edit.tpl.php', [
                    'edit_on_modal' => $editOnModal
                ]
            );

            $generator->generateTemplate(
                $indexTemplateName,
                __DIR__ . '/../../skeleton/template_index.tpl.php'
            );
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