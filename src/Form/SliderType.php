<?php

namespace NS\AceBundle\Form;

use \Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use \Symfony\Component\Form\FormView;
use \Symfony\Component\Form\FormInterface;
use \Symfony\Component\OptionsResolver\OptionsResolver;

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
        $resolver->setDefaults(['color' => 'green', 'switch_type' => 1]);
        $resolver->setRequired(['switch_type']);
        $resolver->setAllowedValues('color', ['green', 'red', 'purple', 'orange', 'dark']);
        $resolver->setAllowedValues('switch_type', [1, 2, 3, 4, 5, 6, 7]);
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (isset($view->vars['attr']['class'])) {
            $view->vars['attr']['class'] .= 'ace ace-switch ace-switch-' . $options['switch_type'];
        } else {
            $view->vars['attr']['class'] = 'ace ace-switch ace-switch-' . $options['switch_type'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return CheckboxType::class;
    }
}
