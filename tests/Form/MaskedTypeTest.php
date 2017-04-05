<?php

namespace NS\AceBundle\Tests\Form;

use \NS\AceBundle\Tests\BaseFormTestType;
use \NS\AceBundle\Form\MaskedType;

/**
 * Description of KnobType
 *
 * @author gnat
 */
class MaskedTypeTest extends BaseFormTestType
{
    /**
     *
     */
    public function testFormType()
    {
        $formData = [
            'masked' => 1,
        ];

        $formBuilder = $this->factory->createBuilder();
        $formBuilder->add('masked', MaskedType::class);
        $form        = $formBuilder->getForm();
        $form->submit($formData);
        $object      = $form->getData();

        $this->assertEquals($formData['masked'], $object['masked']);
        $this->commonTest($form, $formData);
    }
}
