<?php

namespace Umbrella\AdminBundle\DataTable;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Umbrella\AdminBundle\Entity\BaseAdminUser;
use Umbrella\AdminBundle\Lib\DataTable\Action\ButtonAddActionType;
use Umbrella\AdminBundle\Lib\DataTable\Column\ActionColumnType;
use Umbrella\AdminBundle\Lib\DataTable\Column\BooleanColumnType;
use Umbrella\AdminBundle\Lib\DataTable\Column\ColumnType;
use Umbrella\AdminBundle\Lib\DataTable\Column\DateColumnType;
use Umbrella\AdminBundle\Lib\DataTable\Column\PropertyColumnType;
use Umbrella\AdminBundle\Lib\DataTable\ColumnActionBuilder;
use Umbrella\AdminBundle\Lib\DataTable\DataTableBuilder;
use Umbrella\AdminBundle\Lib\DataTable\DataTableType;
use Umbrella\AdminBundle\Lib\Form\SearchType;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;

class UserTableType extends DataTableType
{
    public function __construct(protected UmbrellaAdminConfiguration $config, protected RouterInterface $router)
    {
    }

    public function buildTable(DataTableBuilder $builder, array $options): void
    {
        $builder->addFilter('search', SearchType::class);
        $builder->addAction('add', ButtonAddActionType::class, [
            'route' => 'umbrella_admin_user_edit',
            'text' => 'action.add',
            'xhr' => true,
            'translation_domain' => 'UmbrellaAdmin'
        ]);

        $builder->add('name', ColumnType::class, [
            'render_html' => fn (BaseAdminUser $user) => \sprintf(
                '<a href data-xhr="%s">%s</a>',
                $this->router->generate('umbrella_admin_user_edit', ['id' => $user->id]),
                htmlspecialchars($user->getFullName())
            ),
            'order' => 'ASC',
            'order_by' => ['firstname', 'lastname'],
            'label' => 'label.name',
            'translation_domain' => 'UmbrellaAdmin'
        ]);
        $builder->add('email', PropertyColumnType::class, [
            'label' => 'label.email',
            'translation_domain' => 'UmbrellaAdmin'
        ]);
        $builder->add('createdAt', DateColumnType::class, [
            'label' => 'label.createdAt',
            'translation_domain' => 'UmbrellaAdmin'
        ]);
        $builder->add('active', BooleanColumnType::class, [
            'label' => 'label.active',
            'translation_domain' => 'UmbrellaAdmin'
        ]);
        $builder->add('__action__', ActionColumnType::class, [
            'build' => function (ColumnActionBuilder $builder, BaseAdminUser $e) {
                $builder->editLink([
                    'route' => 'umbrella_admin_user_edit',
                    'route_params' => ['id' => $e->id],
                    'xhr' => true
                ]);
                $builder->deleteLink([
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
    }
}
