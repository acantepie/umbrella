<?= "<?php\n"; ?>

namespace <?= $form->getNamespace(); ?>;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use <?= $entity->getClassName(); ?>;
use Umbrella\CoreBundle\Form\NestedTreeParentType;

class <?= $form->getShortClassName(); ?> extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('parent', NestedTreeParentType::class, [
            'class' => <?= $entity->getShortClassName(); ?>::class,
            'current_node' => $builder->getData(),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => <?= $entity->getShortClassName(); ?>::class,
        ));
    }
}
