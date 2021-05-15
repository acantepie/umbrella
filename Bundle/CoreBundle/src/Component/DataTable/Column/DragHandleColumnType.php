<?php

namespace Umbrella\CoreBundle\Component\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;

class DragHandleColumnType extends ColumnType
{
    public function render($rowData, array $options): string
    {
        return '<i class="mdi mdi-drag"></i>';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('label', null)
            ->setDefault('class', 'drag-handle')
            ->setDefault('is_safe_html', true);
    }
}
