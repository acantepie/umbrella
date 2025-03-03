<?php

namespace Umbrella\CoreBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class NestedEntityType extends AbstractType
{
    protected PropertyAccessorInterface $accessor;

    public function __construct()
    {
        $this->accessor = PropertyAccess::createPropertyAccessorBuilder()
            ->enableExceptionOnInvalidIndex()
            ->getPropertyAccessor();
    }

    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        $disabled = false;
        $disabledLvl = 0;

        /** @var ChoiceView $choice */
        foreach ($view->vars['choices'] as $choice) {
            if (!\is_object($choice->data) && !\is_array($choice->data)) {
                continue;
            }

            $lvl = $this->accessor->getValue($choice->data, $options['level_path']);

            // top lvl must always be 0 (@see _tomselect.scss)
            $cssLvl = max(0, $lvl - $options['min_level']);

            if (null !== $options['disable_node']) {
                if ($choice->data === $options['disable_node']) {
                    $disabled = true;
                    $disabledLvl = $lvl;
                } elseif ($disabled && $lvl <= $disabledLvl) {
                    $disabled = false;
                }

                if ($disabled) {
                    $choice->attr['disabled'] = true;
                }
            }

            $choice->attr['data-lvl'] = $cssLvl;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('left_path', 'left')
            ->setAllowedTypes('left_path', 'string');

        $resolver
            ->setDefault('level_path', 'level')
            ->setAllowedTypes('level_path', 'string');

        $resolver
            ->setDefault('parent_path', 'parent')
            ->setAllowedTypes('parent_path', 'string');

        $resolver
            ->setDefault('disable_node', null)
            ->setAllowedTypes('disable_node', ['null', 'object']);

        $resolver
            ->setDefault('min_level', 0)
            ->setAllowedTypes('min_level', 'int');

        $resolver->setDefault('template', '<div data-lvl="[[ lvl ]]" class="tree-item"> [[ text ]]</div>');

        $resolver->setDefault('query_builder', fn (Options $options) => function (EntityRepository $er) use ($options) {
            return $er->createQueryBuilder('e')
                ->andWhere(\sprintf('e.%s >= :min_level', $options['level_path']))
                ->setParameter('min_level', $options['min_level'])
                ->orderBy(\sprintf('e.%s', $options['left_path']), 'ASC');
        });
    }

    public function getParent(): ?string
    {
        return UmbrellaEntityType::class;
    }
}
