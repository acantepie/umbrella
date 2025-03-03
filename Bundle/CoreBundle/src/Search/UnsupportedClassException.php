<?php

namespace Umbrella\CoreBundle\Search;

class UnsupportedClassException extends \Exception
{
    public function __construct(string $class, ?\Throwable $previous = null)
    {
        parent::__construct(\sprintf('Unsupported class "%s"', $class), 0, $previous);
    }
}
