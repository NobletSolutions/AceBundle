<?php

namespace NS\AceBundle\Tests\Form\Fixtures;

/**
 * Description of Entity
 *
 * @author gnat
 */
class Entity
{
    private $id;

    private $name;

    /**
     * @param integer $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @param string $name
     * @return \NS\AceBundle\Tests\Form\Fixtures\Entity
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     *
     * @param integer $id
     * @return \NS\AceBundle\Tests\Form\Entity
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return 'Does Not Matter';
    }

    /**
     *
     * @return string
     */
    public function getSomeProperty()
    {
        return 'It Matters';
    }
}