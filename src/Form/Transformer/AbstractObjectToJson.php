<?php

namespace NS\AceBundle\Form\Transformer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

abstract class AbstractObjectToJson implements DataTransformerInterface
{
    /** @var EntityManagerInterface */
    protected $entityMgr;

    /** @var string */
    protected $class;

    /** @var string */
    protected $propertyMethod;

    /**
     * @param EntityManagerInterface $entityMgr
     * @param $class
     * @param null $propertyMethod
     */
    public function __construct(EntityManagerInterface $entityMgr, $class, $propertyMethod = null)
    {
        $this->entityMgr = $entityMgr;
        $this->class = $class;
        $this->propertyMethod = $propertyMethod;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        return $this->entityMgr;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getPropertyMethod()
    {
        return $this->propertyMethod;
    }

    /**
     *
     * @param Object $entity
     * @return string
     * @throws \RuntimeException
     */
    public function getProperty($entity)
    {
        if ($this->getPropertyMethod()) {
            $accessor = PropertyAccess::createPropertyAccessor();

            return $accessor->getValue($entity, $this->getPropertyMethod());
        }

        if (!method_exists($entity, '__toString')) {
            throw new \RuntimeException(sprintf("Object of class %s has no __toString", get_class($entity)));
        }

        return $entity->__toString();
    }

    /**
     *
     * @param array $item
     * @param mixed $key
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function walk(&$item, $key)
    {
        $item = $this->getReference($item);
    }

    /**
     * @param integer $id
     * @return object|null
     */
    public function getReference($id)
    {
        return $this->entityMgr->getReference($this->class, $id);
    }
}
