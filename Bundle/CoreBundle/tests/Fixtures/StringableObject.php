<?php

namespace Umbrella\CoreBundle\Tests\Fixtures;

class StringableObject
{

    private string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function __toString(): string
    {
        return $this->text;
    }

}
