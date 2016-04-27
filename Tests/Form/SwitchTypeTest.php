<?php

namespace NS\AceBundle\Tests\Form;

use \NS\AceBundle\Tests\BaseFormTestType;
use \NS\AceBundle\Form\SwitchType;

/**
 * Description of SwitchType
 *
 * @author gnat
 */
class SwitchTypeTest extends BaseFormTestType
{
    /**
     *
     */
    public function testFormType()
    {
        $formData = array(
            'switcher' => 1,
        );

        $formBuilder = $this->factory->createBuilder();
        $formBuilder->add('switcher', SwitchType::class);
        $form        = $formBuilder->getForm();
        $form->submit($formData);
        $object = $form->getData();

        $this->assertEquals($formData['switcher'], $object['switcher']);
        $this->commonTest($form, $formData);
    }
}
