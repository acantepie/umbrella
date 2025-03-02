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
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        if (null !== $options['current_node']) {
            foreach ($view->vars['choices'] as &$choice) {
                if ($choice instanceof ChoiceView && $choice->data === $options['current_node']) {
                    $choice->attr['disabled'] = true;
                }
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('current_node', null)
            ->setAllowedTypes('current_node', ['null', NestedTreeEntityInterface::class])

            ->setNormalizer('choices', fn (Options $options, $value) => $this->getChoices($options['class'], $options['current_node']));
    }

    /**
     * @return NestedTreeEntityInterface[]
     */
    private function getChoices(string $entityClass, ?NestedTreeEntityInterface $currentNode = null): array
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
        return array_filter($nodes, fn (NestedTreeEntityInterface $node) => !$this->isChildOf($node, $currentNode));
    }

    /**
     * Is $a a child of $b ?
     */
    private function isChildOf(NestedTreeEntityInterface $a, NestedTreeEntityInterface $b): bool
    {
        if ($a->getLevel() <= $b->getLevel() || null === $a->getParent()) {
            return false;
        }

        if ($a->getParent() === $b) {
            return true;
        }

        return $this->isChildOf($a->getParent(), $b);
    }

    public function getParent(): ?string
    {
        return NestedTreeType::class;
    }
}
