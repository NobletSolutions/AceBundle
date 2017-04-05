<?php

namespace NS\AceBundle\Test\Filter\Type;

use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 17/05/16
 * Time: 10:23 AM
 */
class DateRangeFilterTypeTest extends TypeTestCase
{

    public function testFormCreation()
    {
        $form = $this->factory->create('NS\AceBundle\Filter\Type\DateRangeFilterType');
        $this->assertInstanceOf('Symfony\Component\Form\Form',$form);
    }
}
