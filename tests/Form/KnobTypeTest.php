<?php

namespace NS\AceBundle\Tests\Form;

use \NS\AceBundle\Tests\BaseFormTestType;
use \NS\AceBundle\Form\KnobType;

/**
 * Description of KnobTypeTest
 *
 * @author gnat
 */
class KnobTypeTest extends BaseFormTestType
{
    /**
     *
     */
    public function testFormType()
    {
        $formData = [
            'knob' => 1,
        ];

        $formBuilder = $this->factory->createBuilder();
        $formBuilder->add('knob', KnobType::class);
        $form        = $formBuilder->getForm();
        $form->submit($formData);
        $object      = $form->getData();

        $this->assertEquals($formData['knob'], $object['knob']);
        $this->commonTest($form, $formData);
    }
}
