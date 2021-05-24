<?php

namespace Umbrella\CoreBundle\UmbrellaFile\Validator\Constraints;

use Symfony\Component\Validator\Constraints\File;
use Umbrella\CoreBundle\UmbrellaFile\Validator\UmbrellaFileValidator;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @property int $maxSize
 */
class UmbrellaFileConstraint extends File
{
    /**
     * @return string
     */
    public function validatedBy()
    {
        return UmbrellaFileValidator::class;
    }
}
