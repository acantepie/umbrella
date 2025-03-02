<?php

namespace Umbrella\CoreBundle\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\DataTable\ActionRenderer;
use Umbrella\CoreBundle\DataTable\DataTableFactory;

class ActionColumnType extends ColumnType
{
    public function __construct(protected DataTableFactory $factory, protected ActionRenderer $renderer)
    {
    }

    public function render(mixed $rowData, array $options): string
    {
        $builder = $this->factory->createColumnActionBuilder();
        call_user_func($options['build'], $builder, $rowData, $options);
        return $this->renderer->renderActions($builder->getActions());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('is_safe_html', true)
            ->setDefault('class', 'text-end text-nowrap')
            ->setDefault('label', null);

        $resolver
            ->setRequired('build')
            ->setAllowedTypes('build', 'callable');
    }
}
