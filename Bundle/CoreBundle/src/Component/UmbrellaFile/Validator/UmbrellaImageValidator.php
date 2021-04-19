<?php

namespace Umbrella\CoreBundle\Component\UmbrellaFile\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\ImageValidator;
use Umbrella\CoreBundle\Component\UmbrellaFile\Storage\FileStorage;
use Umbrella\CoreBundle\Entity\UmbrellaFile;

/**
 * Class UmbrellaImageValidator
 */
class UmbrellaImageValidator extends ImageValidator
{
    private FileStorage $storage;

    /**
     * UmbrellaImageValidator constructor.
     */
    public function __construct(FileStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!is_a($value, UmbrellaFile::class)) {
            return;
        }

        if ($value->_uploadedFile) {
            parent::validate($value->_uploadedFile, $constraint);
        }
    }
}
