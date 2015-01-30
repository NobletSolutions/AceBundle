<?php

namespace NS\AceBundle\Form\Transformer;

use \Symfony\Component\Form\DataTransformerInterface;
use \Doctrine\Common\Persistence\ObjectManager;

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

    /**
     *
     * @param ObjectManager $entityMgr
     * @param string $class
     * @return \NS\AceBundle\Form\Transformer\EntityToJson
     */
    public function __construct(ObjectManager $entityMgr, $class)
    {
        $this->entityMgr = $entityMgr;
        $this->class     = $class;

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