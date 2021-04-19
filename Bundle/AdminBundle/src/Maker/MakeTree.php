<?php

namespace Umbrella\AdminBundle\Maker;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Class MakeTable
 */
class MakeTree extends MakeTable
{
    /**
     * {@inheritdoc}
     */
    public static function getCommandName(): string
    {
        return 'umbrella:make:tree';
    }

    /**
     * MakeTree constructor.
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);
        $this->_structure = self::STRUCTURE_TREE;
    }
}
