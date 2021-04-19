<?php

namespace Umbrella\CoreBundle\Component\DataTable;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Umbrella\CoreBundle\Component\DataTable\DTO\Toolbar;
use Umbrella\CoreBundle\Component\Widget\WidgetBuilder;
use Umbrella\CoreBundle\Component\Widget\WidgetFactory;

class ToolbarBuilder
{
    protected FormFactoryInterface $formFactory;
    protected FormBuilderInterface $formBuilder;

    protected WidgetFactory $widgetFactory;
    protected WidgetBuilder $widgetBuilder;

    protected array $options;

    /**
     * ToolbarBuilder constructor.
     */
    public function __construct(FormFactoryInterface $formFactory, WidgetFactory $widgetFactory, array $options)
    {
        $this->formFactory = $formFactory;
        $this->widgetFactory = $widgetFactory;

        $this->options = $options;
        $this->initBuilder();
    }

    protected function initBuilder()
    {
        $this->formBuilder = $this->formFactory->createNamedBuilder(
            $this->options['toolbar_form_name'],
            FormType::class,
            $this->options['toolbar_form_data'],
            $this->options['toolbar_form_options']
        );

        $this->widgetBuilder = $this->widgetFactory->createBuilder();
    }

    // Filter Api

    public function addFilter($child, string $type = null, array $options = []): self
    {
        $this->formBuilder->add($child, $type, $options);

        return $this;
    }

    public function removeFilter(string $name): self
    {
        $this->formBuilder->remove($name);

        return $this;
    }

    public function hasFilter(string $name): bool
    {
        return $this->formBuilder->has($name);
    }

    // Action api

    public function addWidget($child, string $type = null, array $options = []): self
    {
        $this->widgetBuilder->add($child, $type, $options);

        return $this;
    }

    public function removeWidget(string $name): self
    {
        $this->widgetBuilder->remove($name);

        return $this;
    }

    public function hasWidget(string $name): bool
    {
        return $this->widgetBuilder->has($name);
    }

    // So build it

    public function getToolbar(): Toolbar
    {
        return new Toolbar($this->formBuilder->getForm(), $this->widgetBuilder->getWidget(), $this->options);
    }
}
