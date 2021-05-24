<?php

namespace Umbrella\AdminBundle\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\AdminBundle\Model\AdminUserInterface;
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
        if (!is_a($user, AdminUserInterface::class)) {
            throw new \RuntimeException(sprintf('Can\'t render user::name, expected "%s" class.', AdminUserInterface::class));
        }

        return sprintf(
            '<div><div>%s</div><div class="text-muted">%s</div></div>',
            HtmlUtils::escape($user->getFullName()),
            HtmlUtils::escape($user->getUsername())
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
