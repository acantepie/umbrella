<?php

namespace Umbrella\AdminBundle\Maker;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Umbrella\AdminBundle\Maker\Console\ConsoleStyle;
use Umbrella\AdminBundle\Maker\Console\InputConfiguration;
use Umbrella\AdminBundle\Maker\Generator\Generator;
use Umbrella\AdminBundle\Maker\Utils\MakerUtils;

/**
 * Class MakeTable
 */
class MakeTable extends AbstractMaker
{
    const ACTION_EDIT = 'edit';

    const ACTION_SHOW = 'show';
    const ACTION_DELETE = 'delete';
    const VIEW_MODAL = 'modal';

    const VIEW_PAGE = 'page';
    const VIEW_TYPES = [self::VIEW_MODAL, self::VIEW_PAGE];

    const STRUCTURE_NONE = 'none';
    const STRUCTURE_TREE = 'tree';

    protected EntityManagerInterface $em;

    protected string $_structure = self::STRUCTURE_NONE;
    protected string $_directory = 'Admin';
    protected string $_viewType = self::VIEW_MODAL;
    protected bool $_createTemplate = true;

    /**
     * MakeTable constructor.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getCommandName(): string
    {
        return 'umbrella:make:table';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
        $command->setDescription('Creates a new Table CRUD');
        $command->addArgument('entity', InputArgument::OPTIONAL, 'The class name of the entity to create');
        $command->addOption('force', 'f', InputOption::VALUE_NONE, 'Overwrite existing files');
        $command->addOption('update-schema', 'u', InputOption::VALUE_NONE, 'Update doctrine schema');
    }

    public function interact(InputInterface $input, ConsoleStyle $io, Command $command)
    {
        $this->_directory = $io->askQuestion(new Question('Directory to use for Controller, Table or Form ?', $this->_directory));
        $this->_viewType = $io->askQuestion(new ChoiceQuestion('View type ?', self::VIEW_TYPES, $this->_viewType));
        $this->_createTemplate = $io->askQuestion(new ConfirmationQuestion('Create twig template on project directory ?', $this->_createTemplate));
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $entityName = $input->getArgument('entity');
        $force = $input->getOption('force');
        $updateSchema = $input->getOption('update-schema');

        $entityMeta = $generator->createMetaClass($entityName, 'Entity');
        $repositoryMeta = $generator->createMetaClass($entityName, 'Repository', 'Repository');
        $formMeta = $generator->createMetaClass($entityName, 'Form\\' . $this->_directory, 'Type');
        $tableMeta = $generator->createMetaClass($entityName, 'DataTable\\' . $this->_directory, 'TableType');
        $controllerMeta = $generator->createMetaClass($entityName, 'Controller\\' . $this->_directory, 'Controller');

        $params = [
            'structure' => $this->_structure,
            'i18n_id' => MakerUtils::asRouteName($entityMeta->getShortName()),
            'entity' => $entityMeta,
            'repository' => $repositoryMeta,
            'table' => $tableMeta,
            'controller' => $controllerMeta,
            'form' => $formMeta,
            'routename_prefix' => $controllerMeta->getRouteNamePrefix(),
            'routepath' => $controllerMeta->getRoutePath(),
            'view_type' => $this->_viewType,
            'templatepath_index' => '@UmbrellaAdmin/DataTable/index.html.twig',
        ];

        switch ($this->_viewType) {
            case self::VIEW_MODAL:
                if ($this->_createTemplate) {
                    $params['templatepath_edit'] = $controllerMeta->getTemplatePath() . '/edit.html.twig';
                    $generator->generateTemplate($params['templatepath_edit'], 'datatable/edit_modal.tpl.php', $params);
                } else {
                    $params['templatepath_edit'] = '@UmbrellaAdmin/edit_modal.html.twig';
                }
                break;

            case self::VIEW_PAGE:
                if ($this->_createTemplate) {
                    $params['templatepath_edit'] = $controllerMeta->getTemplatePath() . '/edit.html.twig';
                    $generator->generateTemplate($params['templatepath_edit'], 'datatable/edit.tpl.php', $params);
                } else {
                    $params['templatepath_edit'] = '@UmbrellaAdmin/edit.html.twig';
                }
                break;
        }

        switch ($this->_structure) {
            case self::STRUCTURE_TREE:
                $generator->generateClass($entityMeta->getFilePath(), 'NestedTreeEntity.tpl.php', $params);
                $generator->generateClass($repositoryMeta->getFilePath(), 'NestedTreeRepository.tpl.php', $params);
                $generator->generateClass($formMeta->getFilePath(), 'NestedTreeFormType.tpl.php', $params);
                $generator->generateClass($tableMeta->getFilePath(), 'datatable/NestedTreeTableType.tpl.php', $params);
                $generator->generateClass($controllerMeta->getFilePath(), 'datatable/Controller.tpl.php', $params);
                break;

            default:
                $generator->generateClass($entityMeta->getFilePath(), 'Entity.tpl.php', $params);
                $generator->generateClass($repositoryMeta->getFilePath(), 'Repository.tpl.php', $params);
                $generator->generateClass($formMeta->getFilePath(), 'FormType.tpl.php', $params);
                $generator->generateClass($tableMeta->getFilePath(), 'datatable/TableType.tpl.php', $params);
                $generator->generateClass($controllerMeta->getFilePath(), 'datatable/Controller.tpl.php', $params);
                break;
        }

        $generator->writeChanges($force);

        if ($updateSchema) {
            $this->updateSchema($this->em, $io);
        }

        $io->doneSuccess();
    }
}
