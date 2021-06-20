<?php

namespace Umbrella\CoreBundle\DataTable\Adapter;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\DataTable\DTO\DataTableResult;
use Umbrella\CoreBundle\DataTable\DTO\DataTableState;
use Umbrella\CoreBundle\Utils\FlatCountPaginator;

/**
 * Class EntityAdapter
 */
class EntityAdapter extends DataTableAdapter
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

            ->setDefault('fetch_join_collection', function (Options $options) {
                return !$options['flat_query'];
            }) // Paginator options
            ->setAllowedTypes('fetch_join_collection', 'bool')

            ->setDefault('flat_query', false) // Used this only with qb without OTM or MTM in to optimize db querying
            ->setAllowedTypes('flat_query', 'bool');
    }

    public function getResult(DataTableState $state, array $options): DataTableResult
    {
        if ($options['flat_query']) {
            $paginator = new FlatCountPaginator($this->getQueryBuilder($state, $options), $options['fetch_join_collection']);
        } else {
            $paginator = new Paginator($this->getQueryBuilder($state, $options), $options['fetch_join_collection']);
        }

        return new DataTableResult($paginator);
    }

    public function getQueryBuilder(DataTableState $state, array $options): QueryBuilder
    {
        $dataTable = $state->getDataTable();
        $formData = $state->getFormData();

        $qb = $this->em->createQueryBuilder()
            ->select($options['query_alias'])
            ->from($options['class'], $options['query_alias']);

        if (is_callable($options['query'])) {
            call_user_func($options['query'], $qb, $formData);
        }

        // pagination
        $qb->setFirstResult($state->getStart());
        if ($state->getLength() >= 0) {
            $qb->setMaxResults($state->getLength());
        }

        // order by
        foreach ($state->getOrderBy() as [$column, $direction]) {
            foreach ($column->getOrderBy() as $path) {
                // if path is not a sub property path, prefix it by alias
                if (false === strpos($path, '.')) {
                    $path = sprintf('%s.%s', $options['query_alias'], $path);
                }

                $qb->addOrderBy($path, strtoupper($direction));
            }
        }

        return $qb;
    }
}
