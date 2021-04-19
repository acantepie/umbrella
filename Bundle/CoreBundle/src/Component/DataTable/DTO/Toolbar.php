<?php

namespace Umbrella\CoreBundle\Component\DataTable\DTO;

use Symfony\Component\Form\FormInterface;
use Umbrella\CoreBundle\Component\Widget\DTO\Widget;

class Toolbar
{
    protected FormInterface $form;

    protected Widget $widget;

    protected array $options;

    /**
     * Toolbar constructor.
     */
    public function __construct(FormInterface $form, Widget $widget, array $options)
    {
        $this->form = $form;
        $this->widget = $widget;
        $this->options = $options;
    }

    public function getForm(): FormInterface
    {
        return $this->form;
    }

    public function getWidget(): Widget
    {
        return $this->widget;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getOption(string $name)
    {
        return $this->options[$name];
    }

    public function handleRequest(DataTableRequest $request)
    {
        $this->form->handleRequest($request->getHttpRequest());
        $data = $this->form->getData();

        // Limitation
        // To avoid error on adapter - Toolbar only accept array data from FormType

        if (null !== $data && !is_array($data)) {
            throw new \InvalidArgumentException('Toolbar can only handle array form::getData()');
        }

        $request->setFormData($data ?: []);
    }

    public function submitData(array $data)
    {
        $name = $this->form->getName();

        if (isset($data[$name]) && is_array($data[$name])) {
            $this->form->submit($data[$name]);
        }
    }
}
