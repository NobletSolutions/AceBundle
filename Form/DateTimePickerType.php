<?php

namespace NS\AceBundle\Form;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormView;
use \Symfony\Component\Form\FormInterface;
use \Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of DatePickerType
 *
 * @author gnat
 */
class DateTimePickerType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'date_widget'    => 'single_text',
            'date-format'    => 'm/d/Y',
            'time_widget'    => 'single_text',
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars['attr']['data-date-format'] = $this->dateformatToJS($options['date-format']);
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

    private function dateformatToJS($php_format)
    {
        $SYMBOLS_MATCHING_CLEAN = array(
            // Day
            'l' => 'llll',
            'D' => 'DDD',
            'd' => 'dd',
            'j' => 'j',
            'N' => '',
            'S' => '',
            'w' => '',
            'z' => 'o',
            // Week
            'W' => '',
            // Month
            'F' => 'FFFF',
            'M' => 'MMM',
            'm' => 'mm',
            'n' => 'n',
            't' => '',
            // Year
            'L' => '',
            'o' => '',
            'Y' => 'YYYY',
            'y' => 'yy',
            // Time
            'a' => '',
            'A' => '',
            'B' => '',
            'g' => '',
            'G' => '',
            'h' => '',
            'H' => '',
            'i' => '',
            's' => '',
            'u' => ''
        );

        $SYMBOLS_MATCHING = array(
            // Day
            'l' => 'd',
            'D' => 'd',
            'j' => 'd',
            'z' => 'o',
            // Month
            'F' => 'm',
            'M' => 'm',
            'n' => 'm',
            // Year
            'Y' => 'y',
        );

        $str =  str_replace(array_keys($SYMBOLS_MATCHING_CLEAN), $SYMBOLS_MATCHING_CLEAN, $php_format);
        return str_replace(array_keys($SYMBOLS_MATCHING), $SYMBOLS_MATCHING, $str);
    }
}
