<?php

namespace Umbrella\CoreBundle\Component\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Umbrella\CoreBundle\Utils\Utils;

/**
 * Class CheckBoxColumnType
 */
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
        return $this->columnTemplate(Utils::random(8));
    }

    private function columnTemplate(string $htmlId): string
    {
        return '<div>'
            . '<input class="form-check-input" type="checkbox">'
            . '</div>';
    }

    private function labelTemplate(): string
    {
        return '<div class="dropdown">'
            . '<button class="btn btn-sm p-0 w-100" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
            . '<i class="mdi mdi-dots-vertical"></i>'
            . '</button>'
            . '<div class="dropdown-menu">'
            . '<a class="dropdown-item js-action-select" href="#" data-filter="all">' . $this->translator->trans('common.all') . '</a>'
            . '<a class="dropdown-item js-action-select" href="#" data-filter="none">' . $this->translator->trans('common.none') . '</a>'
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
            ->setDefault('class', 'text-center js-select')
            ->setDefault('label', $this->labelTemplate())
            ->setDefault('label_prefix', null)
            ->setDefault('translation_domain', null)
            ->setDefault('width', '80px')
            ->setDefault('is_safe_html', true);
    }
}
