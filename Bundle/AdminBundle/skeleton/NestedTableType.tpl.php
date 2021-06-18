<?= "<?php\n"; ?>

namespace <?= $namespace ?>;

use <?= $entity->getFullName() ?>;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\DataTable\Column\WidgetColumnType;
use Umbrella\CoreBundle\DataTable\DataTableBuilder;
use Umbrella\CoreBundle\DataTable\DataTableType;
use Umbrella\CoreBundle\DataTable\ToolbarBuilder;
use Umbrella\CoreBundle\Widget\Type\AddLinkType;
use Umbrella\CoreBundle\Widget\Type\RowDeleteLinkType;
use Umbrella\CoreBundle\Widget\Type\RowEditLinkType;
use Umbrella\CoreBundle\Widget\Type\RowMoveLinkType;
use Umbrella\CoreBundle\Widget\WidgetBuilder;

class <?= $class_name ?> extends DataTableType
{
    public function buildToolbar(ToolbarBuilder $builder, array $options = [])
    {
        $builder->addWidget('add', AddLinkType::class, [
            'route' => '<?= $route_name ?>_edit',
<?php if ($edit_on_modal) { ?>
            'xhr' => true
<?php } ?>
        ]);
    }

    public function buildTable(DataTableBuilder $builder, array $options = [])
    {
        $builder->add('id');
        $builder->add('links', WidgetColumnType::class, [
            'build' => function (WidgetBuilder $builder, <?= $entity->getShortName() ?> $e) {

               $builder->add('move', RowMoveLinkType::class, [
                    'route' => '<?= $route_name ?>_move',
                    'route_params' => ['id' => $e->id],
                    'disable_moveup' => $e->isFirstChild(),
                    'disable_movedown' => $e->isLastChild()
                ]);

                $builder->add('edit', RowEditLinkType::class, [
                    'route' => '<?= $route_name ?>_edit',
                    'route_params' => ['id' => $e->id],
<?php if ($edit_on_modal) { ?>
                    'xhr' => true
<?php } ?>
                ]);

                $builder->add('delete', RowDeleteLinkType::class, [
                    'route' => '<?= $route_name ?>_delete',
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