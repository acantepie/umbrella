<?php

namespace Umbrella\AdminBundle\Maker;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Umbrella\AdminBundle\Maker\Console\ConsoleStyle;

/**
 * Convenient abstract class for makers.
 */
abstract class AbstractMaker implements MakerInterface
{
    public function interact(InputInterface $input, ConsoleStyle $io, Command $command)
    {
    }

    protected function updateSchema(EntityManagerInterface $em, ConsoleStyle $io, bool $saveMode = true)
    {
        $schemaTool = new SchemaTool($em);

        $metadatas = $em->getMetadataFactory()->getAllMetadata();
        $schemaTool->updateSchema($metadatas, $saveMode);

        $io->newLine();
        $io->success('Doctrine schema updated');
    }
}
