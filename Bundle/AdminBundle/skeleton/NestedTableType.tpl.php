<?= "<?php\n"; ?>

namespace <?= $namespace ?>;

use <?= $entity->getFullName() ?>;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\DataTable\Action\AddLinkType;
use Umbrella\CoreBundle\DataTable\Column\ActionColumnType;
use Umbrella\CoreBundle\DataTable\ColumnActionBuilder;
use Umbrella\CoreBundle\DataTable\DataTableBuilder;
use Umbrella\CoreBundle\DataTable\DataTableType;

class <?= $class_name ?> extends DataTableType
{
    public function buildTable(DataTableBuilder $builder, array $options)
    {
        $builder->addAction('add', AddLinkType::class, [
            'route' => '<?= $route['name_prefix'] ?>_edit',
<?php if ('modal' === $edit_view_type) { ?>
            'xhr' => true
<?php } ?>
        ]);

        $builder->add('id');
        $builder->add('__action__', ActionColumnType::class, [
            'build' => function (ColumnActionBuilder $builder, <?= $entity->getShortName() ?> $e) {
                $builder->moveLinks([
                    'route' => '<?= $route['name_prefix'] ?>_move',
                    'route_params' => ['id' => $e->id]
                ]);
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

        $builder->useNestedEntityAdapter([
            'class' => <?= $entity->getShortName() ?>::class,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'tree' => true
        ]);
    }
}
