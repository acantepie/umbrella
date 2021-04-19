<?= "<?php\n"; ?>

namespace <?= $table->getNamespace(); ?>;

use <?= $entity->getClassName(); ?>;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\DataTable\Column\PropertyColumnType;
use Umbrella\CoreBundle\Component\DataTable\Column\WidgetColumnType;
use Umbrella\CoreBundle\Component\DataTable\DataTableBuilder;
use Umbrella\CoreBundle\Component\DataTable\DataTableType;
use Umbrella\CoreBundle\Component\DataTable\ToolbarBuilder;
use Umbrella\CoreBundle\Component\Widget\Type\AddLinkType;
use Umbrella\CoreBundle\Component\Widget\Type\RowDeleteLinkType;
use Umbrella\CoreBundle\Component\Widget\Type\RowEditLinkType;
use Umbrella\CoreBundle\Component\Widget\Type\RowMoveLinkType;
use Umbrella\CoreBundle\Component\Widget\WidgetBuilder;

class <?= $table->getShortClassName(); ?> extends DataTableType
{

    public function buildToolbar(ToolbarBuilder $builder, array $options = array())
    {
<?php if ('modal' === $view_type) {  ?>
        $builder->addWidget('add', AddLinkType::class, array(
            'route' => '<?= $routename_prefix; ?>_edit',
            'xhr' => true
        ));
<?php } else { ?>
        $builder->addWidget('add', AddLinkType::class, array(
            'route' => '<?= $routename_prefix; ?>_edit'
        ));
<?php } ?>
    }

    public function buildTable(DataTableBuilder $builder, array $options = array())
    {
        $builder->add('id', PropertyColumnType::class);

        $builder->add('links', WidgetColumnType::class, [
            'build' => function (WidgetBuilder $builder, <?= $entity->getShortClassName(); ?> $entity) {

                $builder->add('move', RowMoveLinkType::class, [
                    'route' => '<?= $routename_prefix; ?>_move',
                    'route_params' => ['id' => $entity->id],
                    'disable_moveup' => $entity->isFirstChild(),
                    'disable_movedown' => $entity->isLastChild()
                ]);

                $builder->add('add', RowEditLinkType::class, [
                    'route' => '<?= $routename_prefix; ?>_edit',
                    'route_params' => ['id' => $entity->id],
<?php if ('modal' !== $view_type) {  ?>
                    'xhr' => false
<?php } ?>
                ]);

                $builder->add('delete', RowDeleteLinkType::class, [
                    'route' => '<?= $routename_prefix; ?>_delete',
                    'route_params' => ['id' => $entity->id]
                ]);
            }
        ]);

        $builder->useNestedEntityAdapter(<?= $entity->getShortClassName(); ?>::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'tree' => true
        ));
    }

}