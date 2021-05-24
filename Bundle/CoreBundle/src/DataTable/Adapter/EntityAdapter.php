<?php

namespace Umbrella\CoreBundle\DataTable\Adapter;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\DataTable\DTO\DataTableRequest;
use Umbrella\CoreBundle\DataTable\DTO\DataTableResult;
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

    public function getResult(DataTableRequest $request, array $options): DataTableResult
    {
        if ($options['flat_query']) {
            $paginator = new FlatCountPaginator($this->getQueryBuilder($request, $options), $options['fetch_join_collection']);
        } else {
            $paginator = new Paginator($this->getQueryBuilder($request, $options), $options['fetch_join_collection']);
        }

        return new DataTableResult($paginator);
    }

    public function getQueryBuilder(DataTableRequest $request, array $options): QueryBuilder
    {
        $dataTable = $request->getDataTable();
        $data = $request->getData();
        $formData = $request->getFormData();

        $qb = $this->em->createQueryBuilder()
            ->select($options['query_alias'])
            ->from($options['class'], $options['query_alias']);

        if (is_callable($options['query'])) {
            call_user_func($options['query'], $qb, $formData);
        }

        // pagination
        if ($dataTable->hasPaging()) {
            if (isset($data['start'])) {
                $qb->setFirstResult($data['start']);
            }

            if (isset($data['length'])) {
                $qb->setMaxResults($data['length']);
            }
        }

        // order by
        $orders = $data['order'] ?? [];
        foreach ($orders as $order) {
            if (!isset($order['column']) || !isset($order['dir']) || !\in_array($order['dir'], ['asc', 'desc'])) {
                continue; // request valid ?
            }

            $idx = $order['column'];
            $dir = $order['dir'];

            if (!$dataTable->hasColumn($idx)) {
                continue;
            }

            $column = $dataTable->getColumn($idx);

            if (!$column->isOrderable()) {
                continue;
            }

            foreach ($column->getOrderBy() as $path) {
                // if path is not a sub property path, prefix it by alias
                if (false === strpos($path, '.')) {
                    $path = sprintf('%s.%s', $options['query_alias'], $path);
                }

                $qb->addOrderBy($path, strtoupper($dir));
            }
        }

        return $qb;
    }
}
