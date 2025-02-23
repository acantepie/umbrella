<?php

namespace Umbrella\CoreBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Umbrella\CoreBundle\Search\EntityIndexer;

#[AsCommand('umbrella:index:entity', 'Reindex #[searchable] entity.')]
class IndexEntityCommand extends Command
{
    private ?SymfonyStyle $io = null;
    private ?string $entityClass = null;

    /**
     * IndexEntityCommand constructor.
     */
    public function __construct(private readonly EntityIndexer $indexer)
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->addArgument('entityClass', InputArgument::OPTIONAL, 'Entity class to index');
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
        $output->setVerbosity(OutputInterface::VERBOSITY_VERY_VERBOSE);
        $this->entityClass = $input->getArgument('entityClass');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($this->entityClass) {
            if (!$this->indexer->isIndexable($this->entityClass)) {
                $this->io->error(sprintf('Entity class %s is not indexable', $this->entityClass));

                return self::FAILURE;
            }

            $this->indexer->indexAllEntitiesOfClass($this->entityClass);

            return self::SUCCESS;
        }

        $this->indexer->indexAll();

        return self::SUCCESS;
    }
}
