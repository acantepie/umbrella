<?php

namespace Umbrella\CoreBundle\Component\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\UmbrellaFile\UmbrellaFileHelper;
use Umbrella\CoreBundle\Entity\UmbrellaFile;
use Umbrella\CoreBundle\Utils\HtmlUtils;

/**
 * Class UmbrellaFileColumnType
 */
class FileColumnType extends PropertyColumnType
{
    protected UmbrellaFileHelper $fileHelper;

    /**
     * ImageColumnType constructor.
     */
    public function __construct(UmbrellaFileHelper $fileHelper)
    {
        parent::__construct();
        $this->fileHelper = $fileHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function renderProperty($value, array $options): string
    {
        if (!$value instanceof UmbrellaFile) {
            return $options['html_empty'];
        }

        $url = $this->fileHelper->getUrl($value);

        return sprintf(
            '<a href="%s" class="text-primary" target="%s" %s>%s</a>',
            $url,
            $options['target'],
            $options['download'] ? 'download' : null,
            HtmlUtils::escape($value->name)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('target', '_self')
            ->setAllowedValues('target', ['_blank', '_self', '_parent', '_top'])

            ->setDefault('download', false)
            ->setAllowedTypes('download', 'bool')

            ->setDefault('html_empty', '')
            ->setAllowedTypes('html_empty', 'string')

            ->setDefault('order_by', null)
            ->setDefault('is_safe_html', true);
    }
}
