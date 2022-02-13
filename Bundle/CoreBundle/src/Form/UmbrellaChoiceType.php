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
    /**
     * @param UmbrellaSelectConfigurator $configurator
     */
    public function __construct(private UmbrellaSelectConfigurator $configurator)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $jsOptions = $this->configurator->getJsOptions($options);

        $view->vars['attr']['is'] = 'umbrella-select';
        $view->vars['attr']['data-options'] = json_encode($jsOptions);

        // never expand
        $view->vars['expanded'] = false;
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
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

        if (!is_array($data) && !$data instanceof \JsonSerializable) {
            throw new \UnexpectedValueException(sprintf('Expected array or JsonSerializable data returned by option[\'expose\'], have %s', gettype($data)));
        }

        $json = json_encode($data);

        if (false === $json) {
            throw new \JsonException('Unable serialize data returned by option[\'expose\']');
        }

        return $json;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $this->configurator->configureOptions($resolver);

        $resolver
            ->setDefault('expose', null)
            ->setAllowedTypes('expose', ['null', 'callable']);

        // override default ChoiceType placeholder normalizer
        $resolver
            ->setNormalizer('placeholder', fn (Options $options, $value) => $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return ChoiceType::class;
    }
}
