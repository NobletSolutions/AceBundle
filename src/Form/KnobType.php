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
    /** @var array */
    private $defaults = [
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

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults($this->defaults);
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        foreach ($this->defaults as $opt => $val) {
            $view->vars['attr']['data-' . $opt] = $options[$opt];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TextType::class;
    }
}
