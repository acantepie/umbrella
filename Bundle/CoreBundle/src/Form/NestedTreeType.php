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
            ->setDefault('select2_options', [
                'dropdownCssClass' => 'select2-tree-dropdown'
            ])
            ->setDefault('query_builder', function (EntityRepository $er) {
                return $er->createQueryBuilder('e')
                    ->orderBy('e.left', 'ASC');
            })
            ->setDefault('expose', function ($entity) {
                if (is_a($entity, NestedTreeEntityInterface::class)) {
                    return ['lvl' => $entity->getLevel()];
                }
            })
            ->setDefault('template', '<div data-lvl="[[ lvl ]]" class="tree-item"> [[ text ]]</div>');
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return UmbrellaEntityType::class;
    }
}
