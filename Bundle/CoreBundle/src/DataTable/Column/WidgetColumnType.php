<?php

namespace Umbrella\CoreBundle\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Widget\WidgetFactory;
use Umbrella\CoreBundle\Widget\WidgetRenderer;

class WidgetColumnType extends ColumnType
{
    protected WidgetFactory $factory;

    protected WidgetRenderer $renderer;

    /**
     * WidgetColumnType constructor.
     */
    public function __construct(WidgetFactory $factory, WidgetRenderer $renderer)
    {
        $this->factory = $factory;
        $this->renderer = $renderer;
    }

    /**
     * {@inheritdoc}
     */
    public function render($rowData, array $options): string
    {
        if ($options['build']) {
            $widgetBuilder = $this->factory->createBuilder();
            call_user_func($options['build'], $widgetBuilder, $rowData, $options);

            return $this->renderer->render($widgetBuilder->getWidget()->createView());
        }

        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('build', null)
            ->setAllowedTypes('build', ['null', 'callable'])

            ->setDefault('class', 'text-end')
            ->setDefault('label', '')

            ->setDefault('is_safe_html', true);
    }
}
