<?php

namespace Umbrella\CoreBundle\Component\Time;

/**
 * Class TimeHelper
 */
class TimeHelper
{
    private DateTimeFormatter $formatter;

    /**
     * TimeHelper constructor.
     */
    public function __construct(DateTimeFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * Returns a single number of years, months, days, hours, minutes or
     * seconds between the specified date times.
     */
    public function diff($from, $to = null, ?string $locale = null): string
    {
        $from = $this->formatter->getDatetimeObject($from);
        $to = $this->formatter->getDatetimeObject($to);

        return $this->formatter->formatDiff($from, $to);
    }
}
