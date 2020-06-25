<?php

namespace NS\AceBundle\Tests\Form;

use \NS\AceBundle\Tests\BaseFormTestType;
use \NS\AceBundle\Form\KnobType;

class KnobTypeTest extends BaseFormTestType
{
    public function testFormType(): void
    {
        $formData = ['knob' => 1 ];

        $formBuilder = $this->factory->createBuilder();
        $formBuilder->add('knob', KnobType::class);
        $form        = $formBuilder->getForm();
        $form->submit($formData);
        $object      = $form->getData();

        $this->assertEquals($formData['knob'], $object['knob']);
        $this->commonTest($form, $formData);
    }
}
