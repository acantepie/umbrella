<?php

namespace Umbrella\AdminBundle\DataTable;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Umbrella\AdminBundle\Entity\BaseUserGroup;
use Umbrella\CoreBundle\Component\DataTable\Column\ManyColumnType;
use Umbrella\CoreBundle\Component\DataTable\Column\PropertyColumnType;
use Umbrella\CoreBundle\Component\DataTable\Column\WidgetColumnType;
use Umbrella\CoreBundle\Component\DataTable\DataTableBuilder;
use Umbrella\CoreBundle\Component\DataTable\DataTableType;
use Umbrella\CoreBundle\Component\DataTable\ToolbarBuilder;
use Umbrella\CoreBundle\Component\Widget\Type\AddLinkType;
use Umbrella\CoreBundle\Component\Widget\Type\RowDeleteLinkType;
use Umbrella\CoreBundle\Component\Widget\Type\RowEditLinkType;
use Umbrella\CoreBundle\Component\Widget\WidgetBuilder;
use Umbrella\CoreBundle\Form\SearchType;

/**
 * Class UserGroupTableType.
 */
class UserGroupTableType extends DataTableType
{
    private ParameterBagInterface $parameters;

    /**
     * UserGroupTableType constructor.
     */
    public function __construct(ParameterBagInterface $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function buildToolbar(ToolbarBuilder $builder, array $options = [])
    {
        $builder->addFilter('search', SearchType::class);
        $builder->addWidget('add_group', AddLinkType::class, [
            'route' => 'umbrella_admin_usergroup_edit',
            'xhr' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildTable(DataTableBuilder $builder, array $options = [])
    {
        $builder->add('title', PropertyColumnType::class);
        $builder->add('roles', ManyColumnType::class);

        $builder->add('links', WidgetColumnType::class, [
            'build' => function (WidgetBuilder $builder, BaseUserGroup $entity) {
                $builder->add('add', RowEditLinkType::class, [
                    'route' => 'umbrella_admin_usergroup_edit',
                    'route_params' => ['id' => $entity->id]
                ]);

                $builder->add('delete', RowDeleteLinkType::class, [
                    'route' => 'umbrella_admin_usergroup_delete',
                    'route_params' => ['id' => $entity->id]
                ]);
            }
        ]);

        $builder->useEntityAdapter([
            'class' => $this->parameters->get('umbrella_admin.user_group.class'),
            'query' => function (QueryBuilder $qb, $formData) {
                if (isset($formData['search'])) {
                    $qb->andWhere('lower(e.title) LIKE :search');
                    $qb->setParameter('search', '%' . $formData['search'] . '%');
                }
            }
        ]);
    }
}
