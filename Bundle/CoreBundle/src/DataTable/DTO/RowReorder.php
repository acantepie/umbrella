<?php

namespace Umbrella\CoreBundle\DataTable\DTO;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class RowReorder
{
    /**
     * @var RowChange[] (mapped by id)
     */
    protected array $rowChanges;

    /**
     * @var RowChange[] (mapped by old position)
     */
    protected array $rowChangesByOldPosition;

    /**
     * RowReorder constructor.
     *
     * @param RowChange[] $rowChanges
     */
    public function __construct(array $rowChanges)
    {
        foreach ($rowChanges as $rowChange) {
            $this->rowChanges[$rowChange->id] = $rowChange;
            $this->rowChangesByOldPosition[$rowChange->oldPosition] = $rowChange;
        }
    }

    public static function createFromRequest(Request $request, string $key = 'changes'): self
    {
        /** @var mixed $changes */
        $changes = $request->query->get($key);

        if (!\is_array($changes)) {
            throw new \InvalidArgumentException(sprintf('Can\'t initialize row reorder from request - argument "%s" must be an array.', $key));
        }

        $rowChanges = [];
        foreach ($changes as $change) {
            $rowChanges[] = new RowChange($change['id'], $change['old'], $change['new']);
        }

        return new self($rowChanges);
    }

    public function applyChanges(EntityManagerInterface $em, string $entityClass, string $changeAttribute, string $idAttribute = 'id'): void
    {
        // Get old value of each entity involved by change

        $dql = sprintf('SELECT e.%s AS id, e.%s AS old_value FROM %s e WHERE e.%s in (:ids)', $idAttribute, $changeAttribute, $entityClass, $idAttribute);
        $q = $em->createQuery($dql)->setParameter('ids', array_keys($this->rowChanges));

        foreach ($q->toIterable() as $i) {
            $this->rowChanges[$i['id']]->oldValue = $i['old_value'];
        }

        $dql = sprintf('UPDATE %s e SET e.%s = :new_value WHERE e.%s = :id', $entityClass, $changeAttribute, $idAttribute);
        $q = $em->createQuery($dql);

        // Set new value to this entity depending of new positions
        foreach ($this->rowChanges as $rowChange) {
            $newValue = $this->rowChangesByOldPosition[$rowChange->newPosition]->oldValue;
            $q
                ->setParameter('id', $rowChange->id)
                ->setParameter('new_value', $newValue)
                ->execute();
        }
    }
}

class RowChange
{
    public string $id;

    public int $oldPosition;

    public int $newPosition;

    public $oldValue;

    /**
     * RowChange constructor.
     */
    public function __construct(string $id, int $oldPosition, int $newPosition)
    {
        $this->id = $id;
        $this->oldPosition = $oldPosition;
        $this->newPosition = $newPosition;
    }
}
