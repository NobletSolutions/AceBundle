<?php

namespace NS\AceBundle\Form;

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
class DatePickerType extends AbstractType
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
            'widget'      => 'single_text',
            'compound'    => false,
            'date-format' => $this->converter->getFormat(),
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        if(isset($view->vars['attr']['class']))
            $view->vars['attr']['class'] .= 'form-control date-picker';
        else
            $view->vars['attr']['class'] = 'form-control date-picker';

        $view->vars['type'] = 'text';
        
        $view->vars['attr']['data-date-format'] = $options['date-format'];
        $view->vars['attr']['placeholder'] = $options['date-format'];
    }
    
    public function getName()
    {
        return 'acedatepicker';
    }
    
    public function getParent()
    {
        return 'date';
    }
}
