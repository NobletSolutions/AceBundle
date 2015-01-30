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

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function __toString()
    {
        return 'Does Not Matter';
    }
}