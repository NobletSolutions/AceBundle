<?php

namespace NS\AceBundle\Test\Filter\Type;

use Symfony\Component\Form\Test\TypeTestCase;
use NS\AceBundle\Filter\Type\DateRangeFilterType;
use Symfony\Component\Form\Form;

class DateRangeFilterTypeTest extends TypeTestCase
{
    public function testFormCreation(): void
    {
        $form = $this->factory->create(DateRangeFilterType::class);
        $this->assertInstanceOf(Form::class,$form);
    }
}
