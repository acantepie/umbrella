<?php

namespace Umbrella\AdminBundle\DataTable;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Umbrella\AdminBundle\Entity\BaseAdminUser;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;
use Umbrella\CoreBundle\DataTable\Column\BooleanColumnType;
use Umbrella\CoreBundle\DataTable\Column\ColumnType;
use Umbrella\CoreBundle\DataTable\Column\DateColumnType;
use Umbrella\CoreBundle\DataTable\Column\WidgetColumnType;
use Umbrella\CoreBundle\DataTable\DataTableBuilder;
use Umbrella\CoreBundle\DataTable\DataTableType;
use Umbrella\CoreBundle\Form\SearchType;
use Umbrella\CoreBundle\Widget\Type\AddLinkType;
use Umbrella\CoreBundle\Widget\Type\RowDeleteLinkType;
use Umbrella\CoreBundle\Widget\Type\RowEditLinkType;
use Umbrella\CoreBundle\Widget\WidgetBuilder;

class UserTableType extends DataTableType
{
    private UmbrellaAdminConfiguration $config;
    private RouterInterface $router;

    /**
     * UserTableType constructor.
     */
    public function __construct(UmbrellaAdminConfiguration $config, RouterInterface $router)
    {
        $this->config = $config;
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function buildTable(DataTableBuilder $builder, array $options)
    {
        $builder->addFilter('search', SearchType::class);
        $builder->addWidget('add', AddLinkType::class, [
            'route' => 'umbrella_admin_user_edit',
            'xhr' => true,
            'text' => 'user.add',
            'translation_domain' => 'UmbrellaAdmin'
        ]);

        $builder->add('name', ColumnType::class, [
            'render_html' => function (BaseAdminUser $user) {
                return sprintf(
                    '<a href data-xhr="%s">%s</a>',
                    $this->router->generate('umbrella_admin_user_edit', ['id' => $user->id]),
                    $user->getFullName()
                );
            },
            'order' => 'ASC',
            'order_by' => ['firstname', 'lastname'],
            'translation_domain' => 'UmbrellaAdmin'
        ]);

        $builder->add('email');
        $builder->add('createdAt', DateColumnType::class, [
            'label' => 'label.created_at',
            'translation_domain' => 'UmbrellaAdmin'
        ]);
        $builder->add('active', BooleanColumnType::class, [
            'label' => 'label.active',
            'translation_domain' => 'UmbrellaAdmin'
        ]);

        $builder->add('links', WidgetColumnType::class, [
            'build' => function (WidgetBuilder $builder, BaseAdminUser $e) {
                $builder->add('add', RowEditLinkType::class, [
                    'route' => 'umbrella_admin_user_edit',
                    'route_params' => ['id' => $e->id],
                    'xhr' => true
                ]);

                $builder->add('delete', RowDeleteLinkType::class, [
                    'route' => 'umbrella_admin_user_delete',
                    'route_params' => ['id' => $e->id]
                ]);
            }
        ]);

        $builder->useEntityAdapter([
            'class' => $this->config->userClass(),
            'query' => function (QueryBuilder $qb, $formData) {
                if (isset($formData['search'])) {
                    $qb->andWhere('lower(e.search) LIKE :search');
                    $qb->setParameter('search', '%' . $formData['search'] . '%');
                }
            }
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('translation_domain', 'UmbrellaAdmin');
    }
}
