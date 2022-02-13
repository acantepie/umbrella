<?php

namespace Umbrella\CoreBundle\DataTable\DTO;

use Twig\Environment;
use Umbrella\CoreBundle\DataTable\Action\ActionType;

class Action
{
    public function __construct(protected ActionType $type, protected array $options)
    {
    }

    public function getType(): ActionType
    {
        return $this->type;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function render(Environment $twig): string
    {
        return $this->type->render($twig, $this->options);
    }
}
