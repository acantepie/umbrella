<?php

namespace Umbrella\AdminBundle\DataTable;

use Twig\Environment;
use Umbrella\AdminBundle\DataTable\DTO\Action;

class ActionRenderer
{
    public function __construct(protected Environment $twig)
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