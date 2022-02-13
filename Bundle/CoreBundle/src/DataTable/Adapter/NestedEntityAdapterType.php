<?php

namespace Umbrella\CoreBundle\DataTable\Adapter;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\DataTable\DTO\DataTableResult;
use Umbrella\CoreBundle\DataTable\DTO\DataTableState;

class NestedEntityAdapterType extends AdapterType implements DoctrineAdapterType
{
    /**
     * EntityCollector constructor.
     */
    public function __construct(protected EntityManagerInterface $em)
    {
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
            ->setAllowedTypes('query', ['callable', 'null']);
    }

    public function getResult(DataTableState $state, array $options): DataTableResult
    {
        $data = $this->getQueryBuilder($state, $options)
            ->getQuery()
            ->getResult();

        return new DataTableResult($data);
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
