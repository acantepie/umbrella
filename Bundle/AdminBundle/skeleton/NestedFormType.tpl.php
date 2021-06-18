<?= "<?php\n"; ?>

namespace <?= $namespace ?>;

use <?= $entity->getFullName() ?>;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Form\NestedTreeParentType;

class <?= $class_name ?> extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('parent', NestedTreeParentType::class, [
            'class' => <?= $entity->getShortName() ?>::class,
            'current_node' => $builder->getData(),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => <?= $entity->getShortName() ?>::class,
        ]);
    }
}
