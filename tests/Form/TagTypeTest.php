<?php

namespace NS\AceBundle\Tests\Form;

use \NS\AceBundle\Tests\BaseFormTestType;
use \NS\AceBundle\Form\TagType;

class TagTypeTest extends BaseFormTestType
{
    public function testFormType(): void
    {
        $formData = ['tag' => 1,];

        $formBuilder = $this->factory->createBuilder();
        $formBuilder->add('tag', TagType::class);
        $form        = $formBuilder->getForm();
        $form->submit($formData);
        $object      = $form->getData();

        $this->assertEquals($formData['tag'], $object['tag']);
        $this->commonTest($form, $formData);
    }
}
