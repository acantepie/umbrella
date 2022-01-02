<?php

namespace Umbrella\AdminBundle\DataTable;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Routing\RouterInterface;
use Umbrella\AdminBundle\Entity\BaseAdminUser;
use Umbrella\AdminBundle\UmbrellaAdminConfiguration;
use Umbrella\CoreBundle\DataTable\Action\ButtonAddActionType;
use Umbrella\CoreBundle\DataTable\Column\ActionColumnType;
use Umbrella\CoreBundle\DataTable\Column\BooleanColumnType;
use Umbrella\CoreBundle\DataTable\Column\ColumnType;
use Umbrella\CoreBundle\DataTable\Column\DateColumnType;
use Umbrella\CoreBundle\DataTable\ColumnActionBuilder;
use Umbrella\CoreBundle\DataTable\DataTableBuilder;
use Umbrella\CoreBundle\DataTable\DataTableType;
use Umbrella\CoreBundle\Form\SearchType;

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
        $builder->addAction('add', ButtonAddActionType::class, [
            'route' => 'umbrella_admin_user_edit',
            'text' => 'user.add',
            'xhr' => true,
            'translation_domain' => 'UmbrellaAdmin'
        ]);

        $builder->add('name', ColumnType::class, [
            'render_html' => function (BaseAdminUser $user) {
                return sprintf(
                    '<a href data-xhr="%s">%s</a>',
                    $this->router->generate('umbrella_admin_user_edit', ['id' => $user->id]),
                    \htmlspecialchars($user->getFullName())
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
}
