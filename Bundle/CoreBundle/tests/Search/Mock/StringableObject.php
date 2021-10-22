<?php

namespace Umbrella\CoreBundle\Tests\Search\Mock;

class StringableObject
{

    private string $text;

    /**
     * @param string $text
     */
    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function __toString()
    {
        return $this->text;
    }

}