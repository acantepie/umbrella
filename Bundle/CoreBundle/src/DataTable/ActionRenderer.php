<?php

namespace Umbrella\CoreBundle\DataTable;

use Twig\Environment;
use Umbrella\CoreBundle\DataTable\DTO\Action;

class ActionRenderer
{
    protected Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
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
