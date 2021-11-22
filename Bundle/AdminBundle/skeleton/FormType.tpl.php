<?php echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

use <?php echo $entity->getFullName(); ?>;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class <?php echo $class_name; ?> extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => <?php echo $entity->getShortName(); ?>::class,
        ]);
    }
}
