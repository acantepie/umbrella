<?php

namespace Umbrella\CoreBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Model\NestedTreeEntityInterface;

class NestedTreeType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('query_builder', fn (EntityRepository $er) => $er->createQueryBuilder('e')
                ->orderBy('e.left', 'ASC'))
            ->setDefault('expose', function ($entity) {
                if (is_a($entity, NestedTreeEntityInterface::class)) {
                    return ['lvl' => $entity->getLevel()];
                } else {
                    return [];
                }
            })
            ->setDefault('template', '<div data-lvl="[[ lvl ]]" class="tree-item"> [[ text ]]</div>')
            ->setDefault('hide_selected', false);
    }

    public function getParent(): ?string
    {
        return UmbrellaEntityType::class;
    }
}
