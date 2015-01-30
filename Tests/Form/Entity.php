<?php

namespace NS\AceBundle\Tests\Form;

/**
 * Description of Entity
 *
 * @author gnat
 */
class Entity
{
    private $id;

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
}