<?php

namespace Umbrella\CoreBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Model\NestedTreeEntityInterface;

/**
 * Class NestedTreeType
 */
class NestedTreeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('query_builder', function (EntityRepository $er) {
                return $er->createQueryBuilder('e')
                    ->orderBy('e.left', 'ASC');
            })
            ->setDefault('select2_options', [
                'dropdownCssClass' => 'select2-tree-dropdown',
            ])
            ->setDefault('expose', function ($entity) {
                if (is_a($entity, NestedTreeEntityInterface::class)) {
                    return [
                        'lvl' => $entity->getLevel(),
                        'indent' => range(0, $entity->getLevel()),
                    ];
                }
            })
            ->setDefault('template_html', $this->getHtmlTemplate());
    }

    private function getHtmlTemplate()
    {
        $mLvl = '[[lvl]]';
        $mClass = 'select2-tree-option';
        $mText = '[[text]]';

        return sprintf('<span data-lvl="%s" class="%s"> <span class="value">%s</span></span>', $mLvl, $mClass, $mText);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return Entity2Type::class;
    }
}
