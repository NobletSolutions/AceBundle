<?php

namespace NS\AceBundle\Ajax;

/**
 * Description of Fields
 *
 * @author gnat
 */
class Fields
{
    private $primaryField;

    private $secondaryField;

    /**
     *
     * @param mixed $primaryField
     * @param mixed $secondaryField
     */
    public function __construct($primaryField, $secondaryField)
    {
        $this->primaryField   = $primaryField;
        $this->secondaryField = $secondaryField;
    }

    /**
     *
     * @return mixed
     */
    public function getPrimaryField()
    {
        return $this->primaryField;
    }

    /**
     *
     * @return mixed
     */
    public function getSecondaryField()
    {
        return $this->secondaryField;
    }

    /**
     *
     * @param mixed $primaryField
     * @return \NS\AceBundle\Ajax\Fields
     */
    public function setPrimaryField($primaryField)
    {
        $this->primaryField = $primaryField;
        return $this;
    }

    /**
     *
     * @param mixed $secondaryField
     * @return \NS\AceBundle\Ajax\Fields
     */
    public function setSecondaryField($secondaryField)
    {
        $this->secondaryField = $secondaryField;
        return $this;
    }
}
