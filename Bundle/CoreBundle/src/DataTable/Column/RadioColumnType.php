<?php

namespace Umbrella\CoreBundle\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;

class RadioColumnType extends ColumnType
{
    /**
     * {@inheritdoc}
     */
    public function render($rowData, array $options): string
    {
        return sprintf('<input class="form-check-input select-handle" type="radio" name="%s">', $options['radio_name']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('radio_name', md5(uniqid('', true)))
            ->setDefault('order', false)
            ->setDefault('class', 'text-center')
            ->setDefault('label', null)
            ->setDefault('translation_domain', null)
            ->setDefault('width', '80px')
            ->setDefault('is_safe_html', true);
    }
}
