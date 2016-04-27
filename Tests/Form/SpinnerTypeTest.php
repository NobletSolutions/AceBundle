<?php

namespace NS\AceBundle\Tests\Form;

use \NS\AceBundle\Tests\BaseFormTestType;
use \NS\AceBundle\Form\SpinnerType;

/**
 * Description of SpinnerTypeTest
 *
 * @author gnat
 */
class SpinnerTypeTest extends BaseFormTestType
{
    /**
     *
     */
    public function testFormType()
    {
        $formData = array(
            'spinner' => 1,
        );

        $formBuilder = $this->factory->createBuilder();
        $formBuilder->add('spinner', SpinnerType::class);
        $form        = $formBuilder->getForm();
        $form->submit($formData);
        $object      = $form->getData();

        $this->assertEquals($formData['spinner'], $object['spinner']);
        $this->commonTest($form, $formData);
    }
}
