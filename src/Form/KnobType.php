<?php

namespace NS\AceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author http://anthonyterrien.com/knob/
 */
class KnobType extends AbstractType
{
    private array $defaults = [
        'min'             => false,
        'max'             => false,
        'width'           => 80,
        'height'          => 80,
        'thickness'       => 0.2,
        'fgColor'         => false,
        'displayPrevious' => false,
        'angleArc'        => false,
        'angleOffset'     => false,
        'displayInput'    => true,
        'linecap'         => 'butt',
        'attr'            => ['class'=>'input-small nsKnob'],
    ];

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults($this->defaults);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        foreach ($this->defaults as $opt => $val) {
            $view->vars['attr']['data-' . $opt] = $options[$opt];
        }
    }

    public function getParent(): string
    {
        return TextType::class;
    }
}
