<?php

namespace NS\AceBundle\Form;

use \Symfony\Component\Form\AbstractType;

/**
 * Description of DateType
 *
 * @author gnat
 */
class DateType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'acedate';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'date';
    }
}
