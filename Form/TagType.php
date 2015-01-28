<?php

namespace NS\AceBundle\Form;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormView;
use \Symfony\Component\Form\FormInterface;
use \Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \Symfony\Component\Form\FormBuilderInterface;
use NS\AceBundle\Form\Transformer\TextToArrayTransformer;

/**
 * Description of SwitchType
 *
 * @author gnat
 * @author mark
 * @author http://loopj.com/jquery-tokeninput/
 */
class TagType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults( array(
            'caseInsensitive'     => true,
            'allowDuplicates'     => false,
            'autocompleteOnComma' => false,
            'arrayOutput'         => false,
        ));
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if(isset($options['source']))
        {
            sort($options['source']);
            $view->vars['attr']['data-source']                = json_encode($options['source']);
        }
        
        $view->vars['attr']['class']                      = 'nsTag';
        $view->vars['attr']['data-case-insensitive']      = $options['caseInsensitive'];
        $view->vars['attr']['data-allow-duplicates']      = $options['allowDuplicates'];
        $view->vars['attr']['data-autocomplete-on-comma'] = $options['autocompleteOnComma'];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($options['arrayOutput'])
            $builder->addViewTransformer(new TextToArrayTransformer());
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
        return 'tag';
    }
}
