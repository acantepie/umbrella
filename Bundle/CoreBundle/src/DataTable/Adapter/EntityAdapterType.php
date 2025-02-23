<?php

namespace Umbrella\CoreBundle\DataTable\Adapter;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\CountWalker;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\DataTable\AdapterException;
use Umbrella\CoreBundle\DataTable\DTO\DataTableResult;
use Umbrella\CoreBundle\DataTable\DTO\DataTableState;

class EntityAdapterType extends AdapterType implements DoctrineAdapterType
{
    public function __construct(protected readonly ManagerRegistry $doctrine)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired('class')
            ->setAllowedTypes('class', 'string')

            ->setDefault('em', null)
            ->setAllowedTypes('em', ['string', 'null'])

            ->setDefault('query_alias', 'e')
            ->setAllowedTypes('query_alias', 'string')

            ->setDefault('query', null)
            ->setAllowedTypes('query', ['callable', 'null'])

            /*
             * Paginator / query options (use for optimization)
             *
             * To fetch large data (~over 1m) use options :
             *  [
             *      'fetch_join_collection' => false,
             *      'use_output_walker' => false,
             *      'use_distinct_hint' => false
             * ]
             */
            ->setDefault('fetch_join_collection', true)
            ->setAllowedTypes('fetch_join_collection', 'bool')

            ->setDefault('use_output_walker', null)
            ->setAllowedTypes('use_output_walker', ['null', 'bool'])

            ->setDefault('use_distinct_hint', true)
            ->setAllowedTypes('use_distinct_hint', 'bool');
    }

    public function getResult(DataTableState $state, array $options): DataTableResult
    {
        $query = $this->getQueryBuilder($state, $options)->getQuery();
        if (false === $options['use_distinct_hint']) {
            $query->setHint(CountWalker::HINT_DISTINCT, false);
        }

        $paginator = new Paginator($query, $options['fetch_join_collection']);
        if (null !== $options['use_output_walker']) {
            $paginator->setUseOutputWalkers($options['use_output_walker']);
        }

        return new DataTableResult($paginator);
    }

    public function getQueryBuilder(DataTableState $state, array $options): QueryBuilder
    {
        $formData = $state->getFormData();

        $em = $this->doctrine->getManager($options['em']);

        if (!$em instanceof EntityManagerInterface) {
            throw new AdapterException('Invalid doctrine manager');
        }

        $qb = $em->createQueryBuilder()
            ->select($options['query_alias'])
            ->from($options['class'], $options['query_alias']);

        if (is_callable($options['query'])) {
            $options['query']($qb, $formData);
        }

        // pagination
        $qb->setFirstResult($state->getStart());
        if ($state->getLength() >= 0) {
            $qb->setMaxResults($state->getLength());
        }

        // order by
        foreach ($state->getOrderBy() as $orderData) {
            $this->addOrderByClosure($qb, $orderData['order_by'], $orderData['direction'], $options);
        }

        return $qb;
    }

    private function addOrderByClosure(QueryBuilder $qb, array $orderBy, string $direction, array $options): void
    {
        foreach ($orderBy as $dqlPath) {
            // if path is not a sub property path, prefix it by alias
            if (!str_contains($dqlPath, '.')) {
                $dqlPath = sprintf('%s.%s', $options['query_alias'], $dqlPath);
            }

            $qb->addOrderBy($dqlPath, strtoupper($direction));
        }
    }
}
