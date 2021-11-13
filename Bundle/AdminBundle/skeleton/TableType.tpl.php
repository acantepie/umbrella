<?= "<?php\n"; ?>

namespace <?= $namespace ?>;

use <?= $entity->getFullName() ?>;
<?php if ($entity_searchable) { ?>
use Doctrine\ORM\QueryBuilder;
use Umbrella\CoreBundle\Form\SearchType;
<?php } ?>
use Umbrella\CoreBundle\DataTable\Column\WidgetColumnType;
use Umbrella\CoreBundle\DataTable\DataTableBuilder;
use Umbrella\CoreBundle\DataTable\DataTableType;
use Umbrella\CoreBundle\Widget\Type\AddLinkType;
use Umbrella\CoreBundle\Widget\Type\RowDeleteLinkType;
use Umbrella\CoreBundle\Widget\Type\RowEditLinkType;
use Umbrella\CoreBundle\Widget\WidgetBuilder;

class <?= $class_name ?> extends DataTableType
{
    public function buildTable(DataTableBuilder $builder, array $options)
    {
<?php if ($entity_searchable) { ?>
        $builder->addFilter('search', SearchType::class);
<?php } ?>
        $builder->addWidget('add', AddLinkType::class, [
            'route' => '<?= $route['name_prefix'] ?>_edit',
<?php if ('modal' === $edit_view_type) { ?>
            'xhr' => true
<?php } ?>
        ]);

        $builder->add('id');
        $builder->add('links', WidgetColumnType::class, [
            'build' => function (WidgetBuilder $builder, <?= $entity->getShortName() ?> $e) {
                $builder->add('edit', RowEditLinkType::class, [
                    'route' => '<?= $route['name_prefix'] ?>_edit',
                    'route_params' => ['id' => $e->id],
<?php if ('modal' === $edit_view_type) { ?>
                    'xhr' => true
<?php } ?>
                ]);

                $builder->add('delete', RowDeleteLinkType::class, [
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