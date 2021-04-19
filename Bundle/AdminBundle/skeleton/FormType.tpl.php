<?= "<?php\n"; ?>

namespace <?= $form->getNamespace(); ?>;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use <?= $entity->getClassName(); ?>;

class <?= $form->getShortClassName(); ?> extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => <?= $entity->getShortClassName(); ?>::class,
        ));
    }
}
