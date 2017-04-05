<?php

namespace NS\AceBundle\Tests\Form;

use \NS\AceBundle\Tests\BaseFormTestType;
use \NS\AceBundle\Form\FileUploadType;

/**
 * Description of SwitchType
 *
 * @author gnat
 */
class FileUploadTypeTest extends BaseFormTestType
{
    /**
     *
     */
    public function testFormType()
    {
        $formData = [
            'file' => 1,
        ];

        $formBuilder = $this->factory->createBuilder();
        $formBuilder->add('file', FileUploadType::class);
        $form        = $formBuilder->getForm();
        $form->submit($formData);
        $object      = $form->getData();

        $this->assertEquals($formData['file'], $object['file']);
        $this->commonTest($form, $formData);
    }
}
