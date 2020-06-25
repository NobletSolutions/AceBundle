<?php

namespace NS\AceBundle\Tests\Form;

use \NS\AceBundle\Tests\BaseFormTestType;
use \NS\AceBundle\Form\SwitchType;

class SwitchTypeTest extends BaseFormTestType
{
    public function testFormType(): void
    {
        $formData = ['switcher' => 1,];

        $formBuilder = $this->factory->createBuilder();
        $formBuilder->add('switcher', SwitchType::class);
        $form        = $formBuilder->getForm();
        $form->submit($formData);
        $object = $form->getData();

        $this->assertEquals($formData['switcher'], $object['switcher']);
        $this->commonTest($form, $formData);
    }
}
