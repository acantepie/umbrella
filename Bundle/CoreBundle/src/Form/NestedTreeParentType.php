<?php

namespace Umbrella\CoreBundle\Form;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Model\NestedTreeEntityInterface;

class NestedTreeParentType extends AbstractType
{
    private EntityManagerInterface $em;

    /**
     * NestedTreeParentType constructor.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if (null !== $options['current_node']) {
            foreach ($view->vars['choices'] as &$choice) {
                if ($choice instanceof ChoiceView && $choice->data === $options['current_node']) {
                    $choice->attr['disabled'] = true;
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('current_node', null)
            ->setAllowedTypes('current_node', ['null', NestedTreeEntityInterface::class])

            ->setNormalizer('choices', function (Options $options, $value) {
                return $this->getChoices($options['class'], $options['current_node']);
            });
    }

    /**
     * @return NestedTreeEntityInterface[]
     */
    private function getChoices(string $entityClass, NestedTreeEntityInterface $currentNode = null): array
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('e');
        $qb->from($entityClass, 'e');
        $qb->orderBy('e.left', 'ASC');

        $nodes = $qb->getQuery()->getResult();

        if (null === $currentNode || null === $currentNode->getId()) {
            return $nodes;
        }

        // exclude all child of currentNode
        return array_filter($nodes, function (NestedTreeEntityInterface $node) use ($currentNode) {
            return !$node->isChildOf($currentNode);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return NestedTreeType::class;
    }
}
