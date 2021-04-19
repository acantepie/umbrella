<?php

namespace Umbrella\CoreBundle\Component\UmbrellaFile\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Image;
use Umbrella\CoreBundle\Component\UmbrellaFile\Validator\UmbrellaImageValidator;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class UmbrellaImageConstraint extends Image
{
    /**
     * @return string
     */
    public function validatedBy()
    {
        return UmbrellaImageValidator::class;
    }
}
