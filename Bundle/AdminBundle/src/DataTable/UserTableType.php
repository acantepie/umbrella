<?php

namespace Umbrella\AdminBundle\DataTable;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Umbrella\AdminBundle\DataTable\Column\UserNameColumnType;
use Umbrella\AdminBundle\Model\AdminUserInterface;
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

/**
 * Class UserTableType.
 */
class UserTableType extends DataTableType
{
    private ParameterBagInterface $parameters;

    /**
     * UserTableType constructor.
     */
    public function __construct(ParameterBagInterface $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function buildTable(DataTableBuilder $builder, array $options = [])
    {
        $builder->addFilter('search', SearchType::class);
        $builder->addWidget('add_user', AddLinkType::class, [
            'route' => 'umbrella_admin_user_edit',
            'xhr' => true,
        ]);

        $builder->add('name', UserNameColumnType::class);
        $builder->add('createdAt', DateColumnType::class);
        $builder->add('active', BooleanColumnType::class);

        $builder->add('links', WidgetColumnType::class, [
            'build' => function (WidgetBuilder $builder, AdminUserInterface $entity) {
                $builder->add('add', RowEditLinkType::class, [
                    'route' => 'umbrella_admin_user_edit',
                    'route_params' => ['id' => $entity->getId()],
                    'xhr' => true
                ]);

                $builder->add('delete', RowDeleteLinkType::class, [
                    'route' => 'umbrella_admin_user_delete',
                    'route_params' => ['id' => $entity->getId()]
                ]);
            }
        ]);

        $builder->useEntityAdapter([
            'class' => $this->parameters->get('umbrella_admin.user.class'),
            'query' => function (QueryBuilder $qb, $formData) {
                if (isset($formData['search'])) {
                    $qb->andWhere('lower(e.search) LIKE :search');
                    $qb->setParameter('search', '%' . $formData['search'] . '%');
                }
            }
        ]);
    }
}
