<?php

namespace NS\AceBundle\Form\Transformer;

use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

abstract class AbstractObjectToJson implements DataTransformerInterface
{
    protected EntityManagerInterface $entityMgr;

    protected string $class;

    protected ?string $propertyMethod = null;

    public function __construct(EntityManagerInterface $entityMgr, string $class, ?string $propertyMethod = null)
    {
        $this->entityMgr      = $entityMgr;
        $this->class          = $class;
        $this->propertyMethod = $propertyMethod;
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityMgr;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getPropertyMethod(): ?string
    {
        return $this->propertyMethod;
    }

    /**
     * @throws RuntimeException
     */
    public function getProperty(object $entity): mixed
    {
        if ($this->getPropertyMethod()) {
            return PropertyAccess::createPropertyAccessor()->getValue($entity, $this->getPropertyMethod());
        }

        if (!method_exists($entity, '__toString')) {
            throw new RuntimeException(sprintf("Object of class %s has no __toString", get_class($entity)));
        }

        return $entity->__toString();
    }

    public function walk(&$item, $key): void
    {
        $item = $this->getReference($item);
    }

    public function getReference($id): ?object
    {
        return $this->entityMgr->getReference($this->class, $id);
    }
}
