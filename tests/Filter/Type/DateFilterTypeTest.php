<?php

namespace NS\AceBundle\Tests\Filter\Type;

use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Form;
use NS\AceBundle\Filter\Type\DateFilterType;

class DateFilterTypeTest extends TypeTestCase
{
    public function testFormCreation(): void
    {
        $form = $this->factory->create(DateFilterType::class);
        $this->assertInstanceOf(Form::class,$form);
    }
}
