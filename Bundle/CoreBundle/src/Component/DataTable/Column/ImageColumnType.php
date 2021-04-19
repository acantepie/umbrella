<?php

namespace Umbrella\CoreBundle\Component\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\UmbrellaFile\UmbrellaFileHelper;
use Umbrella\CoreBundle\Entity\UmbrellaFile;
use Umbrella\CoreBundle\Utils\HtmlUtils;

/**
 * Class ImageColumnType
 */
class ImageColumnType extends PropertyColumnType
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

        $attr = array_merge(['title' => $value->name], $options['image_attr']);
        $url = $this->fileHelper->getImageUrl($value, $options['imagine_filter']);

        return sprintf('<img src="%s" %s>', $url, HtmlUtils::to_attr($attr));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('image_attr', [
                'width' => 80,
                'height' => 80,
            ])
            ->setAllowedTypes('image_attr', 'array')

            ->setDefault('html_empty', '')
            ->setAllowedTypes('html_empty', 'string')

            ->setDefault('imagine_filter', null)
            ->setAllowedTypes('imagine_filter', ['null', 'string'])

            ->setDefault('class', 'text-center')
            ->setDefault('order_by', null)

            ->setDefault('is_safe_html', true);
    }
}
