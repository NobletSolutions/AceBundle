<?php

namespace NS\AceBundle\Form\Extensions;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InputGroupAddonExtension extends AbstractTypeExtension
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(['input-addon-left','input-addon-right']);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (isset($options['input-addon-left'])) {
            $view->vars['addon_left'] = $options['input-addon-left'];
        }

        if (isset($options['input-addon-right'])) {
            $view->vars['addon_right'] = $options['input-addon-right'];
        }
    }

    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }

    public function getExtendedType(): string
    {
        return FormType::class;
    }
}
