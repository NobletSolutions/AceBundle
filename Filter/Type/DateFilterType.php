<?php

namespace NS\AceBundle\Filter\Type;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\OptionsResolver\OptionsResolver;
use \Symfony\Component\Form\FormView;
use \Symfony\Component\Form\FormInterface;
use \NS\AceBundle\Service\DateFormatConverter;
use \Lexik\Bundle\FormFilterBundle\Filter\Form\Type\DateFilterType as ParentDateFilterType;

/**
 * Description of DateFilterType
 *
 * @author gnat
 */
class DateFilterType extends AbstractType
{
    /**
     * @var DateFormatConverter
     */
    protected $converter;

    /**
     *
     * @param DateFormatConverter $converter
     */
    public function __construct(DateFormatConverter $converter)
    {
        $this->converter = ($converter) ?:new DateFormatConverter();
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(array(
                'required'               => false,
                'data_extraction_method' => 'default',
                'widget'   => 'single_text',
                'compound' => false,
                'format'   => $this->converter->getFormat(true),
            ))
            ->setAllowedValues(array(
                'data_extraction_method' => array('default'),
            ))
        ;
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
     * @return string
     */
    public function getParent()
    {
        return ParentDateFilterType::class;
    }
}
