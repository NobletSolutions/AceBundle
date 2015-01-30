<?php

namespace NS\AceBundle\Form\Transformer;

use \Doctrine\Common\Persistence\ObjectManager;
use \Symfony\Component\Form\DataTransformerInterface;
use \Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Description of AbstractObjectToJson
 *
 * @author gnat
 */
abstract class AbstractObjectToJson implements DataTransformerInterface
{
    /* @var $entityMgr ObjectManager */
    protected $entityMgr;

    /* @var $class string */
    protected $class;

    /* @var $propertyMethod string */
    protected $propertyMethod;

    /**
     *
     * @param ObjectManager $entityMgr
     * @param string $class
     * @return \NS\AceBundle\Form\Transformer\EntityToJson
     */
    public function __construct(ObjectManager $entityMgr, $class, $propertyMethod = null)
    {
        $this->entityMgr      = $entityMgr;
        $this->class          = $class;
        $this->propertyMethod = $propertyMethod;

        return $this;
    }

    /**
     *
     * @return ObjectManager
     */
    public function getEntityManager()
    {
        return $this->entityMgr;
    }

    /**
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     *
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
        if ($this->getPropertyMethod())
        {
            $accessor = PropertyAccess::createPropertyAccessor();

            return $accessor->getValue($entity, $this->getPropertyMethod());
        }

        if (!method_exists($entity, '__toString'))
            throw new \RuntimeException(sprintf("Object of class %s has no __toString", get_class($entity)));

        return $entity->__toString();
    }

    /**
     *
     * @param string $propertyMethod
     * @return \NS\AceBundle\Form\Transformer\AbstractObjectToJson
     */
    public function setPropertyMethod($propertyMethod)
    {
        $this->propertyMethod = $propertyMethod;
        return $this;
    }

    /**
     *
     * @param ObjectManager $entityMgr
     * @return \NS\AceBundle\Form\Transformer\AbstractObjectToJson
     */
    public function setEntityManager(ObjectManager $entityMgr)
    {
        $this->entityMgr = $entityMgr;
        return $this;
    }

    /**
     *
     * @param string $class
     * @return \NS\AceBundle\Form\Transformer\AbstractObjectToJson
     */
    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     *
     * @param array $item
     * @param mixed $key
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function walk(&$item, $key)
    {
        $item = $this->getReference($item['id']);
    }

    /**
     *
     * @param integer $id
     * @param mixed $key
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getReference($id)
    {
        return $this->entityMgr->getReference($this->class, $id);
    }
}