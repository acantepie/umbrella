<?php

namespace Umbrella\CoreBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Umbrella\CoreBundle\Search\EntityIndexer;

/**
 * Class IndexEntityCommand.
 */
class IndexEntityCommand extends Command
{
    public const CMD_NAME = 'umbrella:index:entity';

    private EntityIndexer $indexer;

    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * @var string
     */
    private $entityClass;

    /**
     * IndexEntityCommand constructor.
     */
    public function __construct(EntityIndexer $indexer)
    {
        $this->indexer = $indexer;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName(self::CMD_NAME);
        $this->addArgument('entityClass', InputArgument::OPTIONAL, 'Entity class to index');
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $output->setVerbosity(OutputInterface::VERBOSITY_VERY_VERBOSE);
        $this->entityClass = $input->getArgument('entityClass');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->entityClass) {
            if (!$this->indexer->isSearchable($this->entityClass)) {
                $this->io->error(sprintf('Entity class %s is not indexable', $this->entityClass));

                return self::FAILURE;
            }

            $this->indexer->indexAllOfClass($this->entityClass);

            return self::SUCCESS;
        }

        $this->indexer->indexAll();

        return self::SUCCESS;
    }
}
