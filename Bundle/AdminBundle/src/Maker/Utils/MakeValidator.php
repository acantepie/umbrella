<?php

namespace Umbrella\AdminBundle\Maker\Utils;

class MakeValidator
{
    public static function notBlank(string $value = null): string
    {
        if (null === $value || '' === $value) {
            throw new \RuntimeException('This value cannot be blank.');
        }

        return $value;
    }
}
