<?php

namespace NS\AceBundle\Form;

use NS\AceBundle\Service\DateFormatConverter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateRangeType extends AbstractType
{
    protected DateFormatConverter $converter;

    public function __construct(DateFormatConverter $converter = null)
    {
        $this->converter = ($converter) ?:new DateFormatConverter();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'widget'   => 'single_text',
            'compound' => false,
            'format'   => $this->converter->getFormat(true),
            'html5'    => false,
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (isset($view->vars['attr']['class'])) {
            $view->vars['attr']['class'] .= ' form-control date-range';
        } else {
            $view->vars['attr']['class'] = 'form-control date-range';
        }

        $view->vars['attr']['data-date-format'] = strtolower($options['format']);
        $view->vars['attr']['placeholder']      = $options['format'];
    }

    public function getParent(): string
    {
        return DateType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'acedaterange';
    }
}
