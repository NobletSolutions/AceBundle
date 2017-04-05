<?php

namespace NS\AceBundle\Tests\Form\Fixtures;

/**
 * Description of Entity
 *
 * @author gnat
 */
class Entity
{
    /** @var int|null */
    private $id;

    /** @var string */
    private $name;

    /**
     * @param integer $id
     */
    public function __construct($id = null)
    {
        if ($id !== NULL) {
            $this->id = $id;
        }
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'Does Not Matter';
    }

    /**
     * @return string
     */
    public function getSomeProperty()
    {
        return 'It Matters';
    }
}
