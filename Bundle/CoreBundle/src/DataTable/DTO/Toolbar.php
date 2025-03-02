<?php

namespace Umbrella\CoreBundle\DataTable\DTO;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class Toolbar
{
    protected array $formData = [];

    /**
     * @param Action[] $actions
     */
    public function __construct(protected FormInterface $form, protected array $actions, protected array $options)
    {
    }

    public function getForm(): FormInterface
    {
        return $this->form;
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getOption(string $name): mixed
    {
        return $this->options[$name];
    }

    public function getFormData(): array
    {
        return $this->formData;
    }

    public function handleRequest(Request $request): self
    {
        $this->form->handleRequest($request);
        $data = $this->form->getData();

        // Limitation
        // To avoid error on adapter - Toolbar only accept array data from FormType

        if (null !== $data && !is_array($data)) {
            throw new \InvalidArgumentException('Toolbar can only handle array form::getData()');
        }

        $this->formData = $data ?? [];

        return $this;
    }

    public function submitData(array $data): self
    {
        $name = $this->form->getName();

        if (isset($data[$name]) && is_array($data[$name])) {
            $this->form->submit($data[$name]);
        }

        $data = $this->form->getData();
        $this->formData = $data ?? [];

        return $this;
    }
}
