<?php

namespace Umbrella\CoreBundle\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;

class DetailsColumnType extends ColumnType
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
            '<div aria-expanded="%s" class="details-handle"><i class="mdi mdi-chevron-right"></i> <template>%s</template></div>',
            $expanded ? 'true' : 'false',
            $details
        );
    }

    public function isSafeHtml(): bool
    {
        return true;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('label', null)
            ->setDefault('class', 'py-0')
            ->setDefault('width', '60px');

        $resolver
            ->setRequired('render_details')
            ->setAllowedTypes('render_details', 'callable');

        $resolver
            ->setDefault('expanded', false)
            ->setAllowedTypes('expanded', ['boolean', 'callable']);
    }
}
