<?php

namespace Umbrella\AdminBundle\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\AdminBundle\Entity\BaseAdminUser;
use Umbrella\CoreBundle\DataTable\Column\PropertyColumnType;
use Umbrella\CoreBundle\Utils\HtmlUtils;

class UserNameColumnType extends PropertyColumnType
{
    /**
     * {@inheritdoc}
     */
    public function render($rowData, array $options): string
    {
        if (!$rowData instanceof BaseAdminUser) {
            throw new \LogicException(sprintf('UserNameColumnType works only with "%s" entity.', BaseAdminUser::class));
        }

        return sprintf(
            '<strong>%s</strong><div class="text-muted">%s</div>',
            HtmlUtils::escape($rowData->getFullName()),
            HtmlUtils::escape($rowData->email)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver
            ->setDefault('order', 'ASC')
            ->setDefault('order_by', ['firstname', 'lastname'])
            ->setDefault('is_safe_html', true);
    }
}
