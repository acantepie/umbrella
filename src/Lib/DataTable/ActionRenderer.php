<?php

namespace Umbrella\AdminBundle\Lib\DataTable;

use Twig\Environment;
use Umbrella\AdminBundle\Lib\DataTable\DTO\Action;

class ActionRenderer
{
    public function __construct(protected readonly Environment $twig)
    {
    }

    public function renderAction(Action $action): string
    {
        return $action->render($this->twig);
    }

    public function renderActions(array $actions): string
    {
        $h = '';
        foreach ($actions as $action) {
            $h .= $this->renderAction($action);
        }

        return $h;
    }
}
