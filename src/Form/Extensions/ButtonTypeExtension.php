<?php

namespace NS\AceBundle\Form\Extensions;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ButtonTypeExtension extends AbstractTypeExtension
{
    public static function getExtendedTypes(): iterable
    {
        return [ButtonType::class];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(['icon', 'type']);
        $resolver->setAllowedTypes('icon','string');
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (isset($options['icon'])) {
            $view->vars['icon'] = $options['icon'];
        }
    }
}
