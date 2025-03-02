<?php

namespace Umbrella\CoreBundle\DataTable\Adapter;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\DataTable\DTO\DataTableState;

abstract class DoctrineAdapterType extends AdapterType
{
    public function __construct(
        protected readonly ManagerRegistry $doctrine
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $emNormalizer = function (Options $options, $em) {
            if (null === $em) {
                return $this->doctrine->getManagerForClass($options['class']);
            }

            return $em instanceof EntityManagerInterface ? $em : $this->doctrine->getManager($em);
        };

        $resolver
            ->setDefault('em', null)
            ->setAllowedTypes('em', ['string', 'null', EntityManagerInterface::class])
            ->setNormalizer('em', $emNormalizer);
        $resolver
            ->setRequired('class')
            ->setAllowedTypes('class', 'string');
        $resolver
            ->setDefault('query_alias', 'e')
            ->setAllowedTypes('query_alias', 'string');
        $resolver
            ->setDefault('query', null)
            ->setAllowedTypes('query', ['callable', 'null']);
    }

    abstract public function getQueryBuilder(DataTableState $state, array $options): QueryBuilder;
}
