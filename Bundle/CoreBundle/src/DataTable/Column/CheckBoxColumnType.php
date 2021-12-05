<?php

namespace Umbrella\CoreBundle\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class CheckBoxColumnType extends ColumnType
{
    protected TranslatorInterface $translator;

    /**
     * CheckBoxColumnType constructor.
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function render($rowData, array $options): string
    {
        return '<div class="select-handle"><input class="form-check-input" type="checkbox"></div>';
    }

    private function labelTemplate(): string
    {
        return '<div class="dropdown">'
            . '<a class="d-block w-100 text-reset" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
            . '<i class="mdi mdi-dots-vertical"></i>'
            . '</a>'
            . '<div class="dropdown-menu">'
            . '<a class="dropdown-item" href data-tag="dt:selectpage">' . $this->translator->trans('All') . '</a>'
            . '<a class="dropdown-item" href data-tag="dt:unselectpage">' . $this->translator->trans('None') . '</a>'
            . '</div>';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('order', false)
            ->setDefault('class', 'text-center')
            ->setDefault('label', $this->labelTemplate())
            ->setDefault('translation_domain', null)
            ->setDefault('width', '80px')
            ->setDefault('is_safe_html', true);
    }
}
