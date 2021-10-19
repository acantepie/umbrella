<?php

namespace Umbrella\AdminBundle\Form\DataTransformer;

use Umbrella\AdminBundle\Entity\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class RoleToNumbersTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * Transforms the numeric array (1,2,3,4) to a collection of Role (Role[])
     * 
     * @param Array|null $role
     * @return array
     */
    public function transform($roleNumber): array
    {
        $result = [];
        
        if (null === $roleNumber) {
            return $result;
        }
        
        return $this->entityManager
            ->getRepository(Role::class)
            ->findBy(["id" => $roleNumber])
        ;
    }

    /**
     * In this case, the reverseTransform can be empty.
     * 
     * @param type $value
     * @return array
     */
    public function reverseTransform($value): array
    {
        return [];
    }
}
?>
