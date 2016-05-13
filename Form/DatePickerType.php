<?php

namespace NS\AceBundle\Form;

use \NS\AceBundle\Service\DateFormatConverter;
use \Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use \Symfony\Component\Form\FormInterface;
use \Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of DatePickerType
 *
 * @author gnat
 */
class DatePickerType extends AbstractType
{
    /**
     * @var DateFormatConverter
     */
    protected $converter;

    /**
     *
     * @param DateFormatConverter $converter
     */
    public function __construct(DateFormatConverter $converter = null)
    {
        $this->converter = ($converter) ?:new DateFormatConverter();
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'widget'   => 'single_text',
            'compound' => false,
            'format'   => $this->converter->getFormat(true),
        ));
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (isset($view->vars['attr']['class'])) {
            $view->vars['attr']['class'] .= 'form-control date-picker';
        } else {
            $view->vars['attr']['class'] = 'form-control date-picker';
        }

        $view->vars['type'] = 'text';

        $view->vars['attr']['data-date-format'] = strtolower($options['format']);
        $view->vars['attr']['placeholder']      = $options['format'];
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return DateType::class;
    }
}
