<?php

namespace Umbrella\CoreBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Model\NestedTreeEntityInterface;

class NestedTreeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('dropdown_class', 'select2-tree-dropdown')
            ->setDefault('query_builder', function (EntityRepository $er) {
                return $er->createQueryBuilder('e')
                    ->orderBy('e.left', 'ASC');
            })
            ->setDefault('choice_attr', function ($entity) {
                if (is_a($entity, NestedTreeEntityInterface::class)) {
                    return [
                        'data-lvl' => $entity->getLevel(),
                    ];
                }
            })
            ->setDefault('template', '<span data-lvl="[[ lvl ]]" class="select2-tree-option"> <span class="value">[[ text ]]</span></span>');
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return Entity2Type::class;
    }
}
