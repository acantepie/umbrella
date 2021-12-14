<?php

namespace Umbrella\CoreBundle\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;

class DetailsHandleColumnType extends ColumnType
{
    public function render($rowData, array $options): string
    {
        $details = call_user_func($options['render_details'], $rowData, $options);

        if (empty($details)) {
            return '';
        }

        $expanded = is_callable($options['expanded'])
            ? call_user_func($options['expanded'], $rowData, $options)
            : $options['expanded'];

        return sprintf(
            '<a href data-tag="dt:details" data-init-state="%s" class="row-details-handle"><i class="mdi"></i> <template>%s</template></a>',
            $expanded ? 'expanded' : 'collapsed',
            $details
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('label', null)

            ->setRequired('render_details')
            ->setAllowedTypes('render_details', 'callable')

            ->setDefault('expanded', false)
            ->setAllowedTypes('expanded', ['boolean', 'callable'])

            ->setDefault('is_safe_html', true);
    }
}
