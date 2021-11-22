<?php echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

use <?php echo $entity->getFullName(); ?>;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\DataTable\Column\WidgetColumnType;
use Umbrella\CoreBundle\DataTable\DataTableBuilder;
use Umbrella\CoreBundle\DataTable\DataTableType;
use Umbrella\CoreBundle\Widget\Type\AddLinkType;
use Umbrella\CoreBundle\Widget\Type\RowDeleteLinkType;
use Umbrella\CoreBundle\Widget\Type\RowEditLinkType;
use Umbrella\CoreBundle\Widget\Type\RowMoveLinkType;
use Umbrella\CoreBundle\Widget\WidgetBuilder;

class <?php echo $class_name; ?> extends DataTableType
{
    public function buildTable(DataTableBuilder $builder, array $options)
    {
        $builder->addWidget('add', AddLinkType::class, [
            'route' => '<?php echo $route['name_prefix']; ?>_edit',
<?php if ('modal' === $edit_view_type) { ?>
            'xhr' => true
<?php } ?>
        ]);

        $builder->add('id');
        $builder->add('links', WidgetColumnType::class, [
            'build' => function (WidgetBuilder $builder, <?php echo $entity->getShortName(); ?> $e) {

               $builder->add('move', RowMoveLinkType::class, [
                    'route' => '<?php echo $route['name_prefix']; ?>_move',
                    'route_params' => ['id' => $e->id],
                    'disable_moveup' => $e->isFirstChild(),
                    'disable_movedown' => $e->isLastChild()
                ]);

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

        $builder->useNestedEntityAdapter([
            'class' => <?php echo $entity->getShortName(); ?>::class,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'tree' => true
        ]);
    }
}