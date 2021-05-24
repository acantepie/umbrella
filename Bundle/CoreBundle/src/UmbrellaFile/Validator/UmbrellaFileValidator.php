<?php

namespace Umbrella\CoreBundle\UmbrellaFile\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\FileValidator;
use Umbrella\CoreBundle\Entity\UmbrellaFile;
use Umbrella\CoreBundle\UmbrellaFile\Storage\FileStorage;

/**
 * Class UmbrellaFileValidator
 */
class UmbrellaFileValidator extends FileValidator
{
    private FileStorage $storage;

    /**
     * UmbrellaFileValidator constructor.
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
