<?php

namespace NS\AceBundle\Form;

use NS\AceBundle\Service\DateFormatConverter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DatePickerType extends AbstractType
{
    protected DateFormatConverter $converter;

    public function __construct(DateFormatConverter $converter = null)
    {
        $this->converter = ($converter) ?:new DateFormatConverter();
    }

    private array $dataAttributes = [
        'end-date', //infinity
        'start-date', //-infinity
        'week-start', //0 (Sunday)
        'autoclose', //false
        'start-view', //0 'days', //(current month)
        'title', //'',
        'today-btn', //false
        'today-highlight', //false
        'toggle-active', //false
        'z-index-offset',

        //'assume-nearby-year', //false
        //'before-show-day',
        //'before-show-month',
        //'before-show-year',
        //'before-show-decade',
        //'before-show-century',
        //'calendar-weeks', //false
        //'clear-btn', //false
        //'container', //'body',
        //'dates-disabled', //[]
        //'days-of-week-disabled', //[]
        //'days-of-week-highlighted', //[]
        //'default-view-date', //today
        //'disable-touch-keyboard', //false
        //'enable-on-readonly', //true
        //'force-parse', //true
        //'immediate-updates', //false
        //'inputs',
        //'keep-empty-values', //false
        //'keyboard-navigation', //true
        //'language', //'en',
        //'max-view-mode', //4 'centuries',
        //'min-view-mode', //0 'days',
        //'multidate', //false
        //'multidate-separator', //',',
        //'orientation', //'auto',
        //'show-on-focus', //true
        //'templates',
    ];

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'widget'   => 'single_text',
            'compound' => false,
            'format'   => $this->converter->getFormat(true),
            'html5'    => false,
        ]);

        $resolver->setDefined($this->dataAttributes);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (isset($view->vars['attr']['class'])) {
            $view->vars['attr']['class'] .= ' form-control date-picker';
        } else {
            $view->vars['attr']['class'] = 'form-control date-picker';
        }

        $view->vars['type'] = 'text';

        $view->vars['attr']['data-date-format'] = strtolower($options['format']);
        $view->vars['attr']['placeholder']      = $options['format'];

        foreach ($this->dataAttributes as $attribute) {
            if (isset($options[$attribute])) {
                $view->vars['attr']['data-date-'.$attribute] = $attribute;
            }
        }
    }

    public function getParent(): string
    {
        return DateType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'acedatepicker';
    }
}
