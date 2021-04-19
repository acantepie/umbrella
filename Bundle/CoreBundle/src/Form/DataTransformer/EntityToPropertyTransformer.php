<?php

namespace Umbrella\CoreBundle\Form\DataTransformer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Data transformer for single mode (i.e., multiple = false)
 *
 * Class EntityToPropertyTransformer
 */
class EntityToPropertyTransformer implements DataTransformerInterface
{
    protected EntityManagerInterface $em;

    protected string $className;

    protected ?string $textProperty;

    protected string $primaryKey;

    protected PropertyAccessor $accessor;

    public function __construct(EntityManagerInterface $em, string $class, ?string $textProperty = null, string $primaryKey = 'id')
    {
        $this->em = $em;
        $this->className = $class;
        $this->textProperty = $textProperty;
        $this->primaryKey = $primaryKey;
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * Transform entity to array
     *
     * @return array
     */
    public function transform($entity)
    {
        $data = [];
        if (empty($entity)) {
            return $data;
        }

        $text = is_null($this->textProperty)
            ? (string) $entity
            : $this->accessor->getValue($entity, $this->textProperty);

        if (!$this->em->contains($entity)) {
            throw new \InvalidArgumentException(sprintf('Entity "%s" is not managed by orm.', get_class($entity)));
        }

        $value = (string) $this->accessor->getValue($entity, $this->primaryKey);
        $data[$value] = $text;

        return $data;
    }

    /**
     * Transform single id value to an entity
     *
     * @return object|null
     */
    public function reverseTransform($value)
    {
        if (empty($value)) {
            return null;
        }

        // We do not search for a new entry, as it does not exist yet, by definition
        try {
            $entity = $this->em->createQueryBuilder()
                ->select('entity')
                ->from($this->className, 'entity')
                ->where('entity.' . $this->primaryKey . ' = :id')
                ->setParameter('id', $value)
                ->getQuery()
                ->getSingleResult();
        } catch (\Doctrine\ORM\UnexpectedResultException $ex) {
            // this will happen if the form submits invalid data
            throw new TransformationFailedException(sprintf('The choice "%s" does not exist or is not unique', $value));
        }

        if (!$entity) {
            return null;
        }

        return $entity;
    }
}
