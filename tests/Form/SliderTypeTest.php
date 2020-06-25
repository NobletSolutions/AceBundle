<?php

namespace NS\AceBundle\Tests\Form;

use \NS\AceBundle\Tests\BaseFormTestType;
use \NS\AceBundle\Form\SliderType;

class SliderTypeTest extends BaseFormTestType
{
    public function testFormType(): void
    {
        $formData = ['slider' => 1,];

        $formBuilder = $this->factory->createBuilder();
        $formBuilder->add('slider', SliderType::class);
        $form        = $formBuilder->getForm();
        $form->submit($formData);
        $object      = $form->getData();

        $this->assertEquals($formData['slider'], $object['slider']);
        $this->commonTest($form, $formData);
    }
}
