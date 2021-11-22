<?php echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

use <?php echo $entity->getFullName(); ?>;
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

class <?php echo $class_name; ?> extends DataTableType
{
    public function buildTable(DataTableBuilder $builder, array $options)
    {
<?php if ($entity_searchable) { ?>
        $builder->addFilter('search', SearchType::class);
<?php } ?>
        $builder->addWidget('add', AddLinkType::class, [
            'route' => '<?php echo $route['name_prefix']; ?>_edit',
<?php if ('modal' === $edit_view_type) { ?>
            'xhr' => true
<?php } ?>
        ]);

        $builder->add('id');
        $builder->add('links', WidgetColumnType::class, [
            'build' => function (WidgetBuilder $builder, <?php echo $entity->getShortName(); ?> $e) {
                $builder->add('edit', RowEditLinkType::class, [
                    'route' => '<?php echo $route['name_prefix']; ?>_edit',
                    'route_params' => ['id' => $e->id],
<?php if ('modal' === $edit_view_type) { ?>
                    'xhr' => true
<?php } ?>
                ]);

                $builder->add('delete', RowDeleteLinkType::class, [
                    'route' => '<?php echo $route['name_prefix']; ?>_delete',
                    'route_params' => ['id' => $e->id]
                ]);
            }
        ]);

        $builder->useEntityAdapter([
            'class' => <?php echo $entity->getShortName(); ?>::class,
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