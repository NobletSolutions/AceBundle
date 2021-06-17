<?php declare(strict_types=1);

namespace NS\AceBundle\Form\Extensions;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LabelTooltipExtension extends AbstractTypeExtension
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(['label_tooltip']);
        $resolver->setAllowedTypes('label_tooltip', 'array');
        $resolver->setAllowedValues('label_tooltip', static function ($config) {
            if (!isset($config['title'])) {
                return false;
            }

            //icon, position and textSize, title
            $keys = array_keys($config);
            $diff = array_diff($keys, ['icon', 'position', 'textSize', 'title']);

            if (!empty($diff)) {
                return false;
            }

            return true;
        });
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (isset($options['label_tooltip'])) {
            $view->vars['label_tooltip_title'] = $options['label_tooltip']['title'];
            $view->vars['label_tooltip_position'] = $options['label_tooltip']['position'] ?? 'right';
            $view->vars['label_tooltip_icon'] = $options['label_tooltip']['icon'] ?? 'fa-info-circle';
            $view->vars['label_tooltip_textSize'] = $options['label_tooltip']['textSize'] ?? '20px';
        }
    }

    public static function getExtendedTypes(): array
    {
        return [FormType::class];
    }

    public function getExtendedType(): string
    {
        return FormType::class;
    }
}