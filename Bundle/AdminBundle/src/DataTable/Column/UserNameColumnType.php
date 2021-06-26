<?php

namespace Umbrella\AdminBundle\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\AdminBundle\Entity\BaseAdminUser;
use Umbrella\CoreBundle\DataTable\Column\PropertyColumnType;
use Umbrella\CoreBundle\Utils\HtmlUtils;

/**
 * Class UserNameColumnType
 */
class UserNameColumnType extends PropertyColumnType
{
    /**
     * {@inheritdoc}
     */
    public function render($user, array $options): string
    {
        if (!$user instanceof BaseAdminUser) {
            throw new \LogicException(sprintf('UserNameColumnType works only with "%s" entity.', BaseAdminUser::class));
        }

        return sprintf(
            '<div><div>%s</div><div class="text-muted">%s</div></div>',
            HtmlUtils::escape($user->getFullName()),
            HtmlUtils::escape($user->email)
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
