<?php

namespace Umbrella\CoreBundle\Component\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Utils\HtmlUtils;

class DetailsHandleColumnType extends ColumnType
{
    public function render($rowData, array $options): string
    {
        return sprintf(
            '<a href data-onclick="show-details" row-details="%s" class="row-details-handle"><i class="mdi"></i></a>',
            HtmlUtils::escape(call_user_func($options['details_renderer'], $rowData, $options), 'html_attr')
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('label', null)

            ->setRequired('details_renderer')
            ->setAllowedTypes('details_renderer', 'callable')

            ->setDefault('is_safe_html', true);
    }
}
