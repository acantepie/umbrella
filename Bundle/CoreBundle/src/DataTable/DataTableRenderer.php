<?php

namespace Umbrella\CoreBundle\DataTable;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Umbrella\CoreBundle\DataTable\DTO\Column;
use Umbrella\CoreBundle\DataTable\DTO\DataTable;

class DataTableRenderer
{
    public function __construct(
        protected readonly Environment $twig,
        protected readonly RouterInterface $router,
        protected readonly RequestStack $requestStack
    ) {
    }

    public function render(DataTable $table): string
    {
        return $this->twig->render($table->getOption('template'), $this->createView($table));
    }

    protected function createView(DataTable $dataTable): array
    {
        $options = $dataTable->getOptions();

        // js options
        $jsOptions = [];

        if ($options['tree']) {
            $jsOptions['tree'] = [
                'columnIdx' => $options['tree_column_index']
            ];
        }

        // load url
        if ($options['load_route']) {
            $loadUrl = $this->router->generate($options['load_route'], $options['load_route_params']);
        } elseif ($this->requestStack->getMainRequest()) {
            $loadUrl = $this->requestStack->getMainRequest()->getRequestUri();
        } else {
            $loadUrl = null;
        }

        $jsOptions['serverSide'] = true;
        $jsOptions['bFilter'] = false;
        $jsOptions['ajax'] = [
            'url' => $loadUrl,
            'method' => $options['method']
        ];

        if ($options['paging']) {
            $jsOptions['lengthChange'] = $options['length_change'];
            $jsOptions['pageLength'] = $options['page_length'];
            $jsOptions['lengthMenu'] = $options['length_menu'];
        } else {
            $jsOptions['paging'] = false;
        }

        if (0 < $options['scroll_y']) {
            $jsOptions['scrollY'] = $options['scroll_y'];
        }

        $jsOptions['stripeClasses'] = $options['stripe_class'];
        $jsOptions['dom'] = $options['dom'];
        $jsOptions['ordering'] = $options['orderable'];
        $jsOptions['selectable'] = $options['selectable'];

        // columns options
        $jsOptions['columns'] = [];
        $jsOptions['order'] = [];

        foreach ($dataTable->getColumns() as $name => $column) {
            if ($column->isOrderable()) {
                $jsOptions['order'][] = [
                    $name,
                    strtolower((string) $column->getDefaultOrder()),
                ];
            }

            $jsOptions['columns'][] = [
                'orderable' => $column->isOrderable(),
                'className' => $column->getOption('class'),
            ];
        }

        $vars = [];
        $vars['selectable'] = $options['selectable'];
        $vars['toolbar'] = [
            'template' => $options['toolbar_template'],
            'form' => $dataTable->getToolbar()->getForm()->createView(),
            'actions' => $dataTable->getToolbar()->getActions()
        ];
        $vars['template'] = $options['template'];
        $vars['id'] = $options['id'];
        $vars['attr'] = [
            'id' => $options['id'],
            'class' => $options['container_class'],
            'data-options' => json_encode($jsOptions, \JSON_THROW_ON_ERROR),
        ];
        $vars['table_attr'] = [
            'class' => $options['class'] .= ' table js-datatable'
        ];
        $vars['columns'] = array_map(fn (Column $c) => $this->createColumnView($c), $dataTable->getColumns());

        return $vars;
    }

    protected function createColumnView(Column $column): array
    {
        $options = $column->getOptions();

        $vars = [];
        $vars['attr'] = [
            'class' => $options['class'],
            'style' => $options['width'] ? \sprintf('width:%s', $options['width']) : null,
        ];

        $vars['label'] = $options['label'];
        $vars['translation_domain'] = $options['translation_domain'];

        return $vars;
    }
}
