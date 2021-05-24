<?php

namespace Umbrella\CoreBundle\UmbrellaFile\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Image;
use Umbrella\CoreBundle\UmbrellaFile\Validator\UmbrellaImageValidator;

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
