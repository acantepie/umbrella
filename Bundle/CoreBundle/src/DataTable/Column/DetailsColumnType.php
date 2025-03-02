<?php

namespace Umbrella\CoreBundle\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;

class DetailsColumnType extends ColumnType
{
    public function render(mixed $rowData, array $options): string
    {
        $childRow = \call_user_func($options['render_details'], $rowData, $options);
        return empty($childRow) ? '' : sprintf('<a href="#" class="js-toggle-child-row-btn toggle-child-row collapsed"><template>%s</template></a>', $childRow);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('is_safe_html', true)
            ->setDefault('label', null);

        $resolver
            ->setRequired('render_details')
            ->setAllowedTypes('render_details', 'callable');
    }
}
