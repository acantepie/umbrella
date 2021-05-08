<?php

namespace Umbrella\CoreBundle\Component\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Umbrella\CoreBundle\Utils\HtmlUtils;

/**
 * Class EnableColumnType
 */
class BooleanColumnType extends PropertyColumnType
{
    protected TranslatorInterface $translator;

    /**
     * EnableColumnType constructor.
     */
    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct();
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function renderProperty($value, array $options): string
    {
        switch ($value) {
            case true:
                return sprintf(
                    '<span class="badge bg-success">%s %s</span>',
                    HtmlUtils::to_icon($options['yes_icon']),
                    $this->translator->trans($options['yes_value'])
                );

            case false:
                return sprintf(
                    '<span class="badge bg-danger">%s %s</span>',
                    HtmlUtils::to_icon($options['no_icon']),
                    $this->translator->trans($options['no_value'])
                );
            default:
                return '';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('yes_value', 'common.yes')
            ->setAllowedTypes('yes_value', 'string')

            ->setDefault('no_value', 'common.no')
            ->setAllowedTypes('no_value', 'string')

            ->setDefault('yes_icon', 'mdi mdi-check me-1')
            ->setAllowedTypes('yes_icon', 'string')

            ->setDefault('no_icon', 'mdi mdi-cancel me-1')
            ->setAllowedTypes('no_icon', 'string')

            ->setDefault('is_safe_html', true);
    }
}
