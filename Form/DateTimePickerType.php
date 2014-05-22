<?php

namespace NS\AceBundle\Form;

use IntlDateFormatter;
use NS\AceBundle\Service\DateFormatConverter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of DatePickerType
 *
 * @author gnat
 */
class DateTimePickerType extends AbstractType
{
    protected $converter;
    
    public function __construct(DateFormatConverter $converter)
    {
        $this->converter = $converter;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'date_widget'    => 'single_text',
            'date_format'    => $this->converter->getFormat(IntlDateFormatter::MEDIUM),
            'time_widget'    => 'single_text',
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);
        $view->vars['attr']['data-date-format'] = strtolower($options['date_format']);
        $view->vars['attr']['placeholder'] = $options['date_format'];
        $view->vars['type'] = 'text';
    }
    
    public function getName()
    {
        return 'acedatetime';
    }
    
    public function getParent()
    {
        return 'datetime';
    }

}
