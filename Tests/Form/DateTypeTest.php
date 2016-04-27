<?php

namespace NS\AceBundle\Tests\Form;

use \NS\AceBundle\Tests\BaseFormTestType;
use \NS\AceBundle\Form\DatePickerType;
use \NS\AceBundle\Form\DateTimePickerType;
use \NS\AceBundle\Service\DateFormatConverter;
use Symfony\Component\Form\PreloadedExtension;

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
    public function testDatePickerType($formData, $format)
    {
        $this->assertArrayHasKey('date', $formData);

        $form = $this->factory
            ->createBuilder()
            ->add('date', DatePickerType::class)
            ->getForm();
        $form->submit($formData);
        $data = $form->getData();

        $this->assertArrayHasKey('date', $data);
        $this->assertEquals($formData['date'], $data['date']->format($this->converter->fromFormat($format)));
        $this->commonTest($form, $formData);
    }

    /**
     * @dataProvider getDateTimePickerData
     */
    public function testDateTimePickerType($formData, $date)
    {
        $this->assertArrayHasKey('datepicker', $formData);
        $this->assertArrayHasKey('date', $formData['datepicker']);
        $this->assertArrayHasKey('time', $formData['datepicker']);

        $form = $this->factory
            ->createBuilder()
            ->add('datepicker', DateTimePickerType::class)
            ->getForm();

        $form->submit($formData);
        $data = $form->getData();

        $this->assertNotNull($data);
        $this->assertArrayHasKey('datepicker', $data);
        $this->assertEquals($data['datepicker'], $date);
        $this->commonTest($form, $formData);
    }

    /**
     *
     * @return array
     */
    public function getDatePickerData()
    {
        $this->converter = new DateFormatConverter();

        return array(
            array(
                'formData'  => array('date' => '12/07/2014'),
                'format'    => $this->converter->getFormat(true)),
            array(
                'formData'  => array('date' => '01/01/2014'),
                'format'    => $this->converter->getFormat(true)),
            array(
                'formData'  => array('date' => '07/07/2014'),
                'format'    => $this->converter->getFormat(true)),
        );
    }

    /**
     *
     * @return array
     */
    public function getDateTimePickerData()
    {
        return array(
            array(
                'formData'  => array('datepicker' => array('date' => '07/07/2014', 'time' => '12:10')),
                'date'      => new \DateTime('2014-07-07 12:10'),
            ),
            array(
                'formData'  => array('datepicker' => array('date' => '01/01/2014', 'time' => '12:10')),
                'date'      => new \DateTime('2014-01-01 12:10'),
            ),
            array(
                'formData'  => array('datepicker' => array('date' => '12/07/2014', 'time' => '12:10')),
                'date'      => new \DateTime('2014-12-07 12:10'),
            ),
        );
    }

    private $converter;

    public function setUp()
    {
        $this->converter = new DateFormatConverter();
        parent::setUp();
    }

    public function getExtensions()
    {
        if (!$this->converter) {
            $this->converter = new DateFormatConverter();
        }

        $picker = new DateTimePickerType($this->converter);
        $type = new DatePickerType($this->converter);

        return array(new PreloadedExtension(array($type,$picker), array()));
    }
}
