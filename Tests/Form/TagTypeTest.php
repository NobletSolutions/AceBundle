<?php

namespace NS\AceBundle\Tests\Form;

use \NS\AceBundle\Tests\BaseFormTestType;
use \NS\AceBundle\Form\TagType;

/**
 * Description of SwitchType
 *
 * @author gnat
 */
class TagTypeTest extends BaseFormTestType
{
    /**
     *
     */
    public function testFormType()
    {
        $formData = array(
            'tag' => 1,
        );

        $formBuilder = $this->factory->createBuilder();
        $formBuilder->add('tag', new TagType());
        $form        = $formBuilder->getForm();
        $form->submit($formData);
        $object      = $form->getData();

        $this->assertEquals($formData['tag'], $object['tag']);
        $this->commonTest($form, $formData);
    }
}