<?php

namespace NS\AceBundle\Ajax;

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
     * @return void
     */
    public function setPrimaryField($primaryField)
    {
        $this->primaryField = $primaryField;
    }

    /**
     *
     * @param mixed $secondaryField
     * @return void
     */
    public function setSecondaryField($secondaryField)
    {
        $this->secondaryField = $secondaryField;
    }
}
