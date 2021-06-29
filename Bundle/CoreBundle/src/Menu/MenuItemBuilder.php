<?php

namespace Umbrella\CoreBundle\Menu;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Menu\Model\MenuItem;
use Umbrella\CoreBundle\Utils\Utils;

class MenuItemBuilder
{
    protected MenuItem $item;

    protected ?MenuItemBuilder $parent = null;

    /**
     * MenuItemBuilder constructor.
     */
    public function __construct(MenuItem $item, ?MenuItemBuilder $parent = null)
    {
        $this->item = $item;
        $this->parent = $parent;
    }

    public function add(string $id): MenuItemBuilder
    {
        $child = new MenuItem($this->item->getMenu(), $id);
        $this->item->addChild($child);

        return new MenuItemBuilder($child, $this);
    }

    public function setLabel(string $label): MenuItemBuilder
    {
        $this->item->setLabel($label);

        return $this;
    }

    public function setClass(string $class): MenuItemBuilder
    {
        $this->item->setClass($class);

        return $this;
    }

    public function setRoute(?string $route, array $routeParams = []): MenuItemBuilder
    {
        $this->item->setRoute($route);
        $this->item->setRouteParams($routeParams);

        return $this;
    }

    public function setRouteParams(array $routeParams = []): MenuItemBuilder
    {
        $this->item->setRouteParams($routeParams);

        return $this;
    }

    public function addRouteParam(string $key, $value): MenuItemBuilder
    {
        $this->item->addRouteParam($key, $value);

        return $this;
    }

    public function setIcon(string $icon): MenuItemBuilder
    {
        $this->item->setIcon($icon);

        return $this;
    }

    public function setTranslationDomain(?string $translationDomain): MenuItemBuilder
    {
        $this->item->setTranslationDomain($translationDomain);

        return $this;
    }

    public function setSecurity(?string $security): MenuItemBuilder
    {
        $this->item->setSecurity($security);

        return $this;
    }

    public function end(): MenuItemBuilder
    {
        return $this->parent ?: $this;
    }

    public function configure(array $options = []): MenuItemBuilder
    {
        $resolver = new OptionsResolver();
        $resolver
            ->setDefault('label', Utils::humanize($this->item->getId()))
            ->setAllowedTypes('label', 'string')

            ->setDefault('class', '')
            ->setAllowedTypes('class', 'string')

            ->setDefault('translation_domain', 'messages')
            ->setAllowedTypes('translation_domain', ['null', 'string'])

            ->setDefault('icon', null)
            ->setAllowedTypes('icon', ['null', 'string'])

            ->setDefault('security', null)
            ->setAllowedTypes('security', ['null', 'string'])

            ->setDefault('route', null)
            ->setAllowedTypes('route', ['null', 'string'])

            ->setDefault('route_params', [])
            ->setAllowedTypes('route_params', ['array'])

            ->setDefault('children', [])
            ->setAllowedTypes('children', []);

        $resolvedOptions = $resolver->resolve($options);

        $this->item
            ->setTranslationDomain($resolvedOptions['translation_domain'])
            ->setLabel($resolvedOptions['label'])
            ->setClass($resolvedOptions['class'])
            ->setIcon($resolvedOptions['icon'])
            ->setSecurity($resolvedOptions['security'])
            ->setRoute($resolvedOptions['route'])
            ->setRouteParams($resolvedOptions['route_params']);

        foreach ($resolvedOptions['children'] as $id => $childOptions) {
            $this
                ->add($id)
                ->configure($childOptions);
        }

        return $this;
    }
}
