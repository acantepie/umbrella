<?php

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Form\UmbrellaSelect\UmbrellaSelectConfigurator;

class UmbrellaChoiceType extends AbstractType
{
    public function __construct(private readonly UmbrellaSelectConfigurator $configurator)
    {
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $jsOptions = $this->configurator->getJsOptions($options);

        $view->vars['attr']['is'] = 'umbrella-select';
        $view->vars['attr']['data-options'] = json_encode($jsOptions, JSON_THROW_ON_ERROR);

        // never expand
        $view->vars['expanded'] = false;
    }

    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        // If required : always add a placeholder else select can be submitted even if empty
        if (true === $options['required'] && !\is_string($view->vars['placeholder'])) {
            $view->vars['placeholder'] = '';
        }

        if (is_callable($options['expose'])) {
            foreach ($view->vars['choices'] as &$choice) {
                if ($choice instanceof ChoiceView) {
                    $data = $this->getSerializedData($options['expose']($choice->data, $options));
                    if (null !== $data) {
                        $choice->attr['data-json'] = $data;
                    }
                }
            }
        }
    }

    private function getSerializedData($data): ?string
    {
        if (empty($data)) {
            return null;
        }

        return json_encode($data, JSON_THROW_ON_ERROR);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $this->configurator->configureOptions($resolver);

        $resolver
            ->setDefault('expose', null)
            ->setAllowedTypes('expose', ['null', 'callable']);

        // override default ChoiceType placeholder normalizer
        $resolver
            ->setNormalizer('placeholder', fn (Options $options, $value) => $value);
    }

    public function getParent(): ?string
    {
        return ChoiceType::class;
    }
}
