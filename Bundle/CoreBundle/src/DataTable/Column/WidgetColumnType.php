<?php

namespace Umbrella\CoreBundle\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Widget\WidgetFactory;
use Umbrella\CoreBundle\Widget\WidgetRenderer;

class WidgetColumnType extends ColumnType
{
    protected WidgetFactory $factory;
    protected WidgetRenderer $renderer;

    public function __construct(WidgetFactory $factory, WidgetRenderer $renderer)
    {
        $this->factory = $factory;
        $this->renderer = $renderer;
    }

    public function render($rowData, array $options): string
    {
        if ($options['build']) {
            $widgetBuilder = $this->factory->createBuilder();
            call_user_func($options['build'], $widgetBuilder, $rowData, $options);

            return $this->renderer->render($widgetBuilder->getWidget()->createView());
        }

        return '';
    }

    public function isSafeHtml(): bool
    {
        return true;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('class', 'text-end')
            ->setDefault('label', null);

        $resolver
            ->setDefault('build', null)
            ->setAllowedTypes('build', ['null', 'callable']);
    }
}
