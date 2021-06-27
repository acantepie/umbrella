<?php

namespace Umbrella\CoreBundle\DataTable\Adapter;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\DataTable\DTO\DataTableResult;
use Umbrella\CoreBundle\DataTable\DTO\DataTableState;
use Umbrella\CoreBundle\Model\NestedTreeEntityInterface;

class NestedEntityAdapter extends DataTableAdapter
{
    protected EntityManagerInterface $em;

    /**
     * EntityCollector constructor.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired('class')
            ->setAllowedTypes('class', 'string')

            ->setDefault('query_alias', 'e')
            ->setAllowedTypes('query_alias', 'string')

            ->setDefault('query', null)
            ->setAllowedTypes('query', ['callable', 'null'])

            ->setDefault('init_metadata', true)
            ->setAllowedTypes('init_metadata', 'bool');
    }

    public function getResult(DataTableState $state, array $options): DataTableResult
    {
        $data = $this->getQueryBuilder($state, $options)
            ->getQuery()
            ->getResult();

        if ($options['init_metadata']) {
            $this->initMetadata($data);
        }

        return new DataTableResult($data);
    }

    /**
     * @param NestedTreeEntityInterface[] $entities
     */
    private function initMetadata(iterable $entities)
    {
        $currentLvl = -1;
        $lastChildren = [];

        foreach ($entities as $entity) {
            if ($entity->getLevel() > $currentLvl) {
                $entity->setFirstChild(true);
            }

            $currentLvl = $entity->getLevel();

            if (null !== $entity->getParent()) {
                $lastChildren[$entity->getParent()->getId()] = $entity;
            }
        }

        foreach ($lastChildren as $child) {
            $child->setLastChild(true);
        }
    }

    public function getQueryBuilder(DataTableState $state, array $options): QueryBuilder
    {
        $formData = $state->getFormData();

        $qb = $this->em->createQueryBuilder()
            ->select($options['query_alias'])
            ->from($options['class'], $options['query_alias'])
            ->addOrderBy(sprintf('%s.left', $options['query_alias']), 'ASC')
            ->andWhere(sprintf('%s.parent IS NOT NULL', $options['query_alias']));

        if (is_callable($options['query'])) {
            $options['query']($qb, $formData);
        }

        return $qb;
    }
}
