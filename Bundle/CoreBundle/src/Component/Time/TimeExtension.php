<?php

namespace Umbrella\CoreBundle\Component\Time;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Class TimeExtension
 */
class TimeExtension extends AbstractExtension
{
    private TimeHelper $helper;

    /**
     * TimeExtension constructor.
     */
    public function __construct(TimeHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * Returns a list of global functions to add to the existing list.
     *
     * @return array An array of global functions
     */
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'time_diff',
                [$this, 'diff'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter(
                'ago',
                [$this, 'diff'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    public function diff($since, $to = null, ?string $locale = null): string
    {
        return $this->helper->diff($since, $to, $locale);
    }
}
