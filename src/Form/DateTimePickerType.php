<?php

namespace NS\AceBundle\Form;

use NS\AceBundle\Service\DateFormatConverter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTimePickerType extends AbstractType
{
    /** @var DateFormatConverter */
    protected $converter;

    public function __construct(DateFormatConverter $converter = null)
    {
        $this->converter = ($converter) ?:new DateFormatConverter();
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'date_widget' => 'single_text',
            'date_format' => $this->converter->getFormat(true),
            'time_widget' => 'single_text',
            'input'       => 'datetime',
            'html5'       => false, // set to false so that the time widget ends up being text
        ]);
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr']['data-date-format'] = strtolower($options['date_format']);
        $view->vars['attr']['placeholder']      = $options['date_format'];
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return DateTimeType::class;
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        return 'acedatetime';
    }
}
