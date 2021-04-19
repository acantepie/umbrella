<?php

namespace Umbrella\CoreBundle\Component\DataTable\Column;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Class ManyColumnType
 */
class ManyColumnType extends ColumnType
{
    protected PropertyAccessorInterface $accessor;

    /**
     * PropertyColumn constructor.
     */
    public function __construct()
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * {@inheritdoc}
     */
    public function render($rowData, array $options): string
    {
        $many = $this->accessor->getValue($rowData, $options['many_path']);
        if (!is_iterable($many)) {
            throw new \InvalidArgumentException("Attribute {$options['many_path']} must be iterable.");
        }

        $html = '';
        foreach ($many as $one) {
            if (is_callable($options['one_renderer'])) {
                $html .= call_user_func($options['one_renderer'], $one, $options);
            } else {
                $html .= $this->renderOne($one, $options);
            }
        }

        return $html;
    }

    public function renderOne($one, array $options): string
    {
        $value = isset($options['one_path']) && !empty($options['one_path'])
            ? $this->accessor->getValue($one, $options['one_path'])
            : $one;

        return '<span class="badge badge-secondary mb-1 mt-1">' . $value . '</span>';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver
            ->setDefault('many_path', function (Options $options) {
                return $options['id'];
            })
            ->setAllowedTypes('many_path', 'string')

            ->setDefault('one_path', null)
            ->setAllowedTypes('one_path', ['null', 'string'])

            ->setDefault('one_renderer', null)
            ->setAllowedTypes('one_renderer', ['null', 'callable'])

            ->setDefault('is_safe_html', true);
    }
}
