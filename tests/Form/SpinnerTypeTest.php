<?php

namespace NS\AceBundle\Tests\Form;

use \NS\AceBundle\Tests\BaseFormTestType;
use \NS\AceBundle\Form\SpinnerType;

class SpinnerTypeTest extends BaseFormTestType
{
    public function testFormType(): void
    {
        $formData = ['spinner' => 1,];

        $formBuilder = $this->factory->createBuilder();
        $formBuilder->add('spinner', SpinnerType::class);
        $form        = $formBuilder->getForm();
        $form->submit($formData);
        $object      = $form->getData();

        $this->assertEquals($formData['spinner'], $object['spinner']);
        $this->commonTest($form, $formData);
    }
}
