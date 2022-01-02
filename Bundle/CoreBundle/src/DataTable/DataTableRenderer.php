<?php

namespace Umbrella\CoreBundle\DataTable;

use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Umbrella\CoreBundle\DataTable\DTO\Column;
use Umbrella\CoreBundle\DataTable\DTO\DataTable;

class DataTableRenderer
{
    protected Environment $twig;
    protected RouterInterface $router;

    /**
     * DataTableRenderer constructor.
     */
    public function __construct(Environment $twig, RouterInterface $router)
    {
        $this->twig = $twig;
        $this->router = $router;
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
                'expanded' => $options['tree_expanded']
            ];
        }

        $jsOptions['serverSide'] = true;
        $jsOptions['bFilter'] = false;
        $jsOptions['ajax'] = [
            'url' => $options['load_route'] ? $this->router->generate($options['load_route'], $options['load_route_params']) : null,
            'method' => $options['method']
        ];

        if (false !== $options['select']) {
            $jsOptions['select'] = [
                'multiple' => DataTableType::SELECT_MULTIPLE === $options['select']
            ];
        }

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

        $jsOptions['dom'] = $options['dom'];
        $jsOptions['ordering'] = $options['orderable'];

        // columns options
        $jsOptions['columns'] = [];
        $jsOptions['order'] = [];

        foreach ($dataTable->getColumns() as $name => $column) {
            if ($column->isOrderable()) {
                $jsOptions['order'][] = [
                    $name,
                    strtolower($column->getDefaultOrder()),
                ];
            }

            $jsOptions['columns'][] = [
                'orderable' => $column->isOrderable(),
                'className' => $column->getOption('class'),
            ];
        }

        $vars = [];
        $vars['toolbar'] = [
            'template' => $options['toolbar_template'],
            'form' => $dataTable->getToolbar()->getForm()->createView(),
            'actions' => $dataTable->getToolbar()->getActions(),
            'bulkActions' => $dataTable->getToolbar()->getBulkActions()
        ];
        $vars['template'] = $options['template'];
        $vars['id'] = $options['id'];
        $vars['attr'] = [
            'id' => $options['id'],
            'class' => $options['class'],
            'data-options' => json_encode($jsOptions),
        ];
        $vars['table_attr'] = [
            'class' => $options['table_class'] .= ' table js-datatable'
        ];
        $vars['columns'] = array_map(function (Column $c) {
            return $this->createColumnView($c);
        }, $dataTable->getColumns());

        return $vars;
    }

    protected function createColumnView(Column $column): array
    {
        $options = $column->getOptions();

        $vars = [];
        $vars['attr'] = [
            'class' => $options['class'],
            'style' => $options['width'] ? sprintf('width:%s', $options['width']) : null,
        ];

        $vars['label'] = $options['label'];
        $vars['translation_domain'] = $options['translation_domain'];

        return $vars;
    }
}
