<?php

namespace Umbrella\CoreBundle\DataTable\Adapter;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\DataTable\DTO\DataTableResult;
use Umbrella\CoreBundle\DataTable\DTO\DataTableState;

class NestedEntityAdapterType extends DoctrineAdapterType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('left_path', 'left')
            ->setAllowedTypes('left_path', 'string');
        $resolver
            ->setDefault('level_path', 'level')
            ->setAllowedTypes('level_path', 'string');
        $resolver
            ->setDefault('min_level', 1)
            ->setAllowedTypes('min_level', 'int');
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
        /** @var EntityManagerInterface $em */
        $em = $options['em'];

        $formData = $state->getFormData();

        $qb = $em->createQueryBuilder()
            ->select($options['query_alias'])
            ->from($options['class'], $options['query_alias'])
            ->addOrderBy(\sprintf('%s.%s', $options['query_alias'], $options['left_path']), 'ASC');

        if ($options['min_level'] > 0) {
            $qb->andWhere(\sprintf('%s.%s >= :__minLevel__', $options['query_alias'], $options['level_path']));
            $qb->setParameter('__minLevel__', $options['min_level']);
        }

        if (\is_callable($options['query'])) {
            $options['query']($qb, $formData, $state->getDataTable()->getOptions());
        }

        return $qb;
    }
}
