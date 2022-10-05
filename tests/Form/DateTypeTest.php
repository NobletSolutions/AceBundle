<?php

namespace NS\AceBundle\Tests\Form;

use DateTime;
use \NS\AceBundle\Tests\BaseFormTestType;
use \NS\AceBundle\Form\DatePickerType;
use \NS\AceBundle\Form\DateTimePickerType;
use \NS\AceBundle\Service\DateFormatConverter;
use Symfony\Component\Form\PreloadedExtension;

class DateTypeTest extends BaseFormTestType
{
    /** @dataProvider getDatePickerData */
    public function testDatePickerType(array $formData, string $format): void
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

    /** @dataProvider getDateTimePickerData */
    public function testDateTimePickerType(array $formData, DateTime $date): void
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

    public function getDatePickerData(): array
    {
        $this->converter = new DateFormatConverter();

        return [
            [
                'formData' => ['date' => '12/07/2014'],
                'format'   => $this->converter->getFormat(true)],
            [
                'formData' => ['date' => '01/01/2014'],
                'format'   => $this->converter->getFormat(true)],
            [
                'formData' => ['date' => '07/07/2014'],
                'format'   => $this->converter->getFormat(true)],
        ];
    }

    public function getDateTimePickerData(): array
    {
        return [
            [
                'formData' => ['datepicker' => ['date' => '07/07/2014', 'time' => '12:10']],
                'date'     => new DateTime('2014-07-07 12:10'),
            ],
            [
                'formData' => ['datepicker' => ['date' => '01/01/2014', 'time' => '12:10']],
                'date'     => new DateTime('2014-01-01 12:10'),
            ],
            [
                'formData' => ['datepicker' => ['date' => '12/07/2014', 'time' => '12:10']],
                'date'     => new DateTime('2014-12-07 12:10'),
            ],
        ];
    }

    private ?DateFormatConverter $converter = null;

    public function setUp(): void
    {
        $this->converter = new DateFormatConverter();
        parent::setUp();
    }

    public function getExtensions(): array
    {
        if (!$this->converter) {
            $this->converter = new DateFormatConverter();
        }

        $picker = new DateTimePickerType($this->converter);
        $type   = new DatePickerType();

        return [new PreloadedExtension([$type, $picker], [])];
    }
}
