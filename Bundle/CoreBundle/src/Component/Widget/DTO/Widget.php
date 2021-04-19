<?php

namespace Umbrella\CoreBundle\Component\Widget\DTO;

use Umbrella\CoreBundle\Component\Widget\Type\WidgetType;

class Widget
{
    protected string $name;

    protected WidgetType $type;

    protected array $options;

    /**
     * @var Widget[]
     */
    protected array $children = [];

    protected ?WidgetView $view = null;

    /**
     * Widget constructor.
     */
    public function __construct(string $name, WidgetType $type, array $options, array $children = [])
    {
        $this->name = $name;
        $this->type = $type;
        $this->options = $options;
        $this->children = $children;
    }

    public function createView(): WidgetView
    {
        if (null === $this->view) {
            $this->view = new WidgetView();

            $this->view->vars['name'] = $this->name;
            $this->view->vars['block_name'] = $this->getBlockName($this->type->getBlockPrefix());

            $this->type->buildView($this->view, $this->options);
            foreach ($this->children as $name => $child) {
                $this->view->children[$name] = $child->createView();
            }
        }

        return $this->view;
    }

    private function getBlockName(string $blockPrefix): string
    {
        if (empty($blockPrefix) || 'widget' === $blockPrefix) {
            return 'base_widget';
        }

        if (\str_ends_with($blockPrefix, '_widget')) {
            return $blockPrefix;
        }

        return sprintf('%s_widget', $blockPrefix);
    }
}
