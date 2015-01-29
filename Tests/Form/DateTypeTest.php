<?php

namespace NS\AceBundle\Tests\Form;

use \NS\AceBundle\Tests\BaseFormTestType;
use \NS\AceBundle\Form\DatePickerType;
use \NS\AceBundle\Form\DateTimePickerType;
use \NS\AceBundle\Service\DateFormatConverter;

/**
 * Description of DatePickerType
 *
 * @author gnat
 */
class DateTypeTest extends BaseFormTestType
{
    /**
     * @dataProvider getDatePickerData
     */
    public function testDatePickerType($formData, $converter, $format)
    {
        $this->assertArrayHasKey('date', $formData);

        $form = $this->factory
            ->createBuilder()
            ->add('date', new DatePickerType($converter))
            ->getForm();
        $form->submit($formData);
        $data = $form->getData();

        $this->assertArrayHasKey('date', $data);
        $this->assertEquals($formData['date'], $data['date']->format($converter->fromFormat($format)));
        $this->commonTest($form, $formData);
    }

    /**
     * @dataProvider getDateTimePickerData
     */
    public function testDateTimePickerType($formData, $converter, $format, $date)
    {
        $this->assertArrayHasKey('datepicker', $formData);
        $this->assertArrayHasKey('date', $formData['datepicker']);
        $this->assertArrayHasKey('time', $formData['datepicker']);

        $form = $this->factory
            ->createBuilder()
            ->add('datepicker', new DateTimePickerType($converter))
            ->getForm();

        $form->submit($formData);
        $data = $form->getData();

        $this->assertNotNull($data);
        $this->assertArrayHasKey('datepicker', $data);
        $this->assertEquals($data['datepicker'], $date);
        $this->commonTest($form, $formData);
    }

    public function getDatePickerData()
    {
        $converter = new DateFormatConverter();

        return array(
            array(
                'formData'  => array('date' => '12/07/2014'),
                'converter' => $converter,
                'format'    => $converter->getFormat(true)),
            array(
                'formData'  => array('date' => '01/01/2014'),
                'converter' => $converter,
                'format'    => $converter->getFormat(true)),
            array(
                'formData'  => array('date' => '07/07/2014'),
                'converter' => $converter,
                'format'    => $converter->getFormat(true)),
//            array(
//                'formData'  => array('date' => '12/07/14'),
//                'converter' => $converter,
//                'format'    => $converter->getFormat()),
//            array(
//                'formData'  => array('date' => '01/01/14'),
//                'converter' => $converter,
//                'format'    => $converter->getFormat()),
//            array(
//                'formData'  => array('date' => '07/07/14'),
//                'converter' => $converter,
//                'format'    => $converter->getFormat()),
        );
    }

    public function getDateTimePickerData()
    {
        $converter   = new DateFormatConverter();
        $longFormat  = $converter->getFormat(true);
//        $shortFormat = $converter->getFormat();

        return array(
            array(
                'formData'  => array('datepicker' => array('date' => '07/07/2014',
                        'time' => '12:10')),
                'converter' => $converter,
                'format'    => $longFormat,
                'date'      => new \DateTime('2014-07-07 12:10'),
            ),
            array(
                'formData'  => array('datepicker' => array('date' => '01/01/2014',
                        'time' => '12:10')),
                'converter' => $converter,
                'format'    => $longFormat,
                'date'      => new \DateTime('2014-01-01 12:10'),
            ),
            array(
                'formData'  => array('datepicker' => array('date' => '12/07/2014',
                        'time' => '12:10')),
                'converter' => $converter,
                'format'    => $longFormat,
                'date'      => new \DateTime('2014-12-07 12:10'),
            ),
//            array(
//                'formData'  => array('date' => '12/07/14'),
//                'converter' => $converter,
//                'format'    => $shortFormat),
//            array(
//                'formData'  => array('date' => '01/01/14'),
//                'converter' => $converter,
//                'format'    => $shortFormat),
//            array(
//                'formData'  => array('date' => '07/07/14'),
//                'converter' => $converter,
//                'format'    => $shortFormat),
        );
    }
}