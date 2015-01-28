<?php

namespace NS\AceBundle\Form;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormView;
use \Symfony\Component\Form\FormInterface;
use \Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of SliderType
 *
 * @author gnat
 */
class SliderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults( array('color' => 'green'));
        
        $resolver->setAllowedValues(array('color'=>  array('green','red','purple','orange','dark')));
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if(isset($view->vars['attr']['class']))
            $view->vars['attr']['class'] .= 'ace ace-switch ace-switch-'.$options['switchtype'];
        else
            $view->vars['attr']['class'] = 'ace ace-switch ace-switch-'.$options['switchtype'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'slider';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'checkbox';
    }
}
