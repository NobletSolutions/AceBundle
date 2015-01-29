<?php

namespace NS\AceBundle\Tests\Form;

use \NS\AceBundle\Tests\BaseFormTestType;
use \NS\AceBundle\Form\RecordsPerPageType;

/**
 * Description of RecordsPerPageType
 *
 * @author gnat
 */
class RecordsPerPageTypeTest extends BaseFormTestType
{
    /**
     * @dataProvider getData
     */
    public function testFormType($formData, $expectValue)
    {
        $mockSession = $this->getMockBuilder('\Symfony\Component\HttpFoundation\Session\Session')
            ->disableOriginalConstructor()
            ->setMethods(array('isStarted', 'get'))
            ->getMock();

        $mockSession->expects($this->once())
            ->method('isStarted')
            ->willReturn(false);

        $formBuilder = $this->factory->createBuilder();
        $formBuilder->add('rpp', new RecordsPerPageType($mockSession));
        $form        = $formBuilder->getForm();
        $form->submit($formData);
        $data        = $form->getData();

        if ($expectValue)
            $this->assertEquals($formData['rpp'], $data['rpp']);
        else
            $this->assertEmpty($data['rpp']);

        $this->commonTest($form, $formData);
    }

    /**
     * 
     */
    public function getData()
    {
        return array(
            array('formData' => array('rpp' => 5), 'expectValue' => true),
            array('formData' => array('rpp' => 10), 'expectValue' => true),
            array('formData' => array('rpp' => 20), 'expectValue' => true),
            array('formData' => array('rpp' => 30), 'expectValue' => true),
            array('formData' => array('rpp' => 50), 'expectValue' => true),
            array('formData' => array('rpp' => 75), 'expectValue' => true),
            array('formData' => array('rpp' => 100), 'expectValue' => true),
        );
    }
}