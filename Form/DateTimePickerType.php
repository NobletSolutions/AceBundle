<?php

namespace NS\AceBundle\Form;

use \NS\AceBundle\Service\DateFormatConverter;
use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormInterface;
use \Symfony\Component\Form\FormView;
use \Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of DatePickerType
 *
 * @author gnat
 */
class DateTimePickerType extends AbstractType
{
    protected $converter;

    /**
     *
     * @param DateFormatConverter $converter
     */
    public function __construct(DateFormatConverter $converter)
    {
        $this->converter = $converter;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'date_widget'    => 'single_text',
            'date_format'    => $this->converter->getFormat(true),
            'time_widget'    => 'single_text',
        ));
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr']['data-date-format'] = strtolower($options['date_format']);
        $view->vars['attr']['placeholder'] = $options['date_format'];
        $view->vars['type'] = 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'acedatetime';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'datetime';
    }
}
