<?php

namespace NS\AceBundle\Form;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormView;
use \Symfony\Component\Form\FormInterface;
use \Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
    public function configureOptions(OptionsResolver $resolver)
    {
        $colors      = array('green', 'red', 'purple', 'orange', 'dark');
        $switchtypes = array(1, 2, 3, 4, 5, 6, 7);
        $resolver->setDefaults(array('color' => 'green', 'switchtype' => 1));
        $resolver->setRequired(array('switchtype'));
        $resolver->setAllowedValues(array('color' => $colors, 'switchtype' => $switchtypes));
        
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if(isset($view->vars['attr']['class'])) {
            $view->vars['attr']['class'] .= 'ace ace-switch ace-switch-' . $options['switchtype'];
        } else {
            $view->vars['attr']['class'] = 'ace ace-switch ace-switch-' . $options['switchtype'];
        }
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
