<?php

namespace Umbrella\AdminBundle\DataTable;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\AdminBundle\DataTable\Column\UserNameColumnType;
use Umbrella\AdminBundle\Entity\BaseAdminUser;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;
use Umbrella\CoreBundle\DataTable\Column\BooleanColumnType;
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

    /**
     * UserTableType constructor.
     */
    public function __construct(UmbrellaAdminConfiguration $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function buildTable(DataTableBuilder $builder, array $options): void
    {
        $builder->addFilter('search', SearchType::class);
        $builder->addWidget('add', AddLinkType::class, [
            'route' => 'umbrella_admin_user_edit',
            'xhr' => true,
            'text' => 'user.add',
            'translation_domain' => 'UmbrellaAdmin'
        ]);

        $builder->add('name', UserNameColumnType::class, [
            'label' => 'label.name',
            'translation_domain' => 'UmbrellaAdmin'
        ]);
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

    /**
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('translation_domain', 'UmbrellaAdmin');
    }
}
