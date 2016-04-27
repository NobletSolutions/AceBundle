<?php

namespace NS\AceBundle\Form;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormView;
use \Symfony\Component\Form\FormInterface;
use \Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of KnobType
 *
 * @author gnat
 * @author mark
 * @author http://anthonyterrien.com/knob/
 */
class KnobType extends AbstractType
{
    private $defaults = array(
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
        'linecap'         => 'butt', //"round"
    );

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array_merge($this->defaults, array('attr' => array('class' => 'input-small nsKnob'))));
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
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        foreach($this->defaults as $opt => $val) {
            $view->vars['attr']['data-' . $opt] = $options[$opt];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'knob';
    }
}
