<?php

namespace NS\AceBundle\Form;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormView;
use \Symfony\Component\Form\FormInterface;
use \Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of SwitchType
 *
 * @author gnat
 * @author mark
 * @author http://loopj.com/jquery-tokeninput/
 */
class SpinnerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults( array(
            'min'           => 0,
            'max'           => 100,
            'step'          => 1,
            'touchscreen'   => false, //built for touchscreen
            'on_sides'      => false,
            'pos_neg'     => false,
            'attr'        => array('class' => 'nsSpinner')
        ));
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $opts = array();

        foreach(array('min', 'max', 'step', 'on_sides') as $opt)
            $opts[$opt] = $options[$opt];

        if($options['pos_neg'])
            $opts += array('icon_up' => 'icon-plus smaller-75', 'icon_down' => 'icon-minus smaller-75', 'btn_up_class' => 'btn-success' , 'btn_down_class' => 'btn-danger');

        if($options['touchscreen'])
            $opts['touch_spinner'] = true;

        $view->vars['attr']['data-options'] = json_encode($opts);
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
        return 'spinner';
    }
}
