<?php

namespace NS\AceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SwitchType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['switch_type' => 1, 'hide_label' => false]);
        $resolver->setAllowedValues('switch_type', range(1, 7));
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (isset($view->vars['attr']['class'])) {
            $view->vars['attr']['class'] .= ' ace ace-switch ace-switch-' . $options['switch_type'];
        } else {
            $view->vars['attr']['class'] = 'ace ace-switch ace-switch-' . $options['switch_type'];
        }

        $view->vars['hidelabel'] = $options['hide_label'];
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return CheckboxType::class;
    }
}
