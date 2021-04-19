<?php

namespace Umbrella\CoreBundle\Component\DataTable;

use Twig\Environment;
use Umbrella\CoreBundle\Component\DataTable\DTO\Column;
use Umbrella\CoreBundle\Component\DataTable\DTO\DataTable;
use Umbrella\CoreBundle\Component\DataTable\DTO\Toolbar;

// FIXME use View Object ?
class DataTableRenderer
{
    protected Environment $twig;

    /**
     * DataTableRenderer constructor.
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function render(DataTable $table): string
    {
        return $this->twig->render($table->getOption('template'), $this->view($table));
    }

    public function renderToolbar(Toolbar $toolbar): string
    {
        return $this->twig->render($toolbar->getOption('toolbar_template'), $this->toolbarView($toolbar));
    }

    protected function view(DataTable $dataTable): array
    {
        $options = $dataTable->getOptions();

        $vars = [];
        $vars['toolbar'] = $dataTable->getToolbar();
        $vars['template'] = $options['template'];
        $vars['id'] = $options['id'];
        $vars['attr'] = [
            'id' => $options['id'],
            'class' => 'umbrella-datatable-container',
            'data-options' => $this->getJsOptions($dataTable),
        ];
        $vars['table_attr'] = [
            'class' => $options['class'] .= ' datatable'
        ];
        $vars['columns'] = array_map(function (Column $c) {
            return $this->columnView($c);
        }, $dataTable->getColumns());

        return $vars;
    }

    protected function getJsOptions(DataTable $dataTable): array
    {
        $options = $dataTable->getOptions();

        // js options
        $jsOptions = [];

        $jsOptions['tree'] = $options['tree'];
        $jsOptions['tree_state'] = $options['tree_state'];

        $jsOptions['serverSide'] = true;
        $jsOptions['bFilter'] = false;
        $jsOptions['ajax'] = [
            'url' => $options['load_url'],
        ];
        $jsOptions['ajax_data'] = [
            '_dtid' => $options['id'],
        ];

        if ($options['paging']) {
            $jsOptions['lengthChange'] = $options['length_change'];
            $jsOptions['pageLength'] = $options['page_length'];
            $jsOptions['lengthMenu'] = $options['length_menu'];
        } else {
            $jsOptions['paging'] = false;
        }

        $jsOptions['fixedHeader'] = $options['fixed_header'];

        if ($options['rowreorder_url']) {
            $jsOptions['rowReorder'] = [
                'update' => false,
                'url' => $options['rowreorder_url'],
            ];
        }

        $jsOptions['poll_interval'] = $options['poll_interval'];
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

            $jsOptions['columns'][] = $this->getColumnJsOptions($column);
        }

        return $jsOptions;
    }

    protected function columnView(Column $column): array
    {
        $options = $column->getOptions();

        $vars = [];
        $vars['attr'] = [
            'class' => $options['class'],
            'style' => $options['width'] ? sprintf('width:%s', $options['width']) : null,
        ];

        $vars['label'] = $options['label'];
        $vars['label_prefix'] = $options['label_prefix'];
        $vars['translation_domain'] = $options['translation_domain'];

        return $vars;
    }

    protected function getColumnJsOptions(Column $column): array
    {
        return [
            'orderable' => $column->isOrderable(),
            'className' => $column->getOption('class'),
        ];
    }

    protected function toolbarView(Toolbar $toolbar): array
    {
        $options = $toolbar->getOptions();

        $vars = [];
        $vars['template'] = $options['toolbar_template'];
        $vars['form'] = $toolbar->getForm()->createView();
        $vars['widget'] = $toolbar->getWidget()->createView();

        return $vars;
    }
}
