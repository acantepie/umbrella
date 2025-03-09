<?= "<?php\n"; ?>

namespace <?= $namespace ?>;

use <?= $entity->getFullName() ?>;
<?php if ($entity_searchable) { ?>
use Doctrine\ORM\QueryBuilder;
use Umbrella\AdminBundle\Lib\Form\SearchType;
<?php } ?>
use Umbrella\AdminBundle\Lib\DataTable\Action\ButtonAddActionType;
use Umbrella\AdminBundle\Lib\DataTable\Column\ActionColumnType;
use Umbrella\AdminBundle\Lib\DataTable\ColumnActionBuilder;
use Umbrella\AdminBundle\Lib\DataTable\DataTableBuilder;
use Umbrella\AdminBundle\Lib\DataTable\DataTableType;

class <?= $class_name ?> extends DataTableType
{
    public function buildTable(DataTableBuilder $builder, array $options): void
    {
<?php if ($entity_searchable) { ?>
        $builder->addFilter('search', SearchType::class);
<?php } ?>
        $builder->addAction('add', ButtonAddActionType::class, [
            'route' => '<?= $route['name_prefix'] ?>_edit',
<?php if ('modal' === $edit_view_type) { ?>
            'xhr' => true
<?php } ?>
        ]);

        $builder->add('id');
        $builder->add('__action__', ActionColumnType::class, [
            'build' => function (ColumnActionBuilder $builder, <?= $entity->getShortName() ?> $e) {
                $builder->editLink([
                    'route' => '<?= $route['name_prefix'] ?>_edit',
                    'route_params' => ['id' => $e->id],
<?php if ('modal' === $edit_view_type) { ?>
                    'xhr' => true
<?php } ?>
                ]);
                $builder->deleteLink([
                    'route' => '<?= $route['name_prefix'] ?>_delete',
                    'route_params' => ['id' => $e->id]
                ]);
            }
        ]);

        $builder->useEntityAdapter([
            'class' => <?= $entity->getShortName() ?>::class,
<?php if ($entity_searchable) { ?>
            'query' => function(QueryBuilder $qb, array $formData) {
                if (isset($formData['search'])) {
                    $qb->andWhere('LOWER(e.search) LIKE :search');
                    $qb->setParameter('search', '%' . $formData['search'] . '%');
                }
            }
<?php } ?>
        ]);
    }

}
