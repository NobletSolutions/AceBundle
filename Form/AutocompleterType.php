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
class AutocompleterType extends AbstractType
{
    private $router;

    public function setRouter(\Symfony\Component\Routing\RouterInterface $router)
    {
        $this->router = $router;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setOptional(array('route','autocompleteUrl'));

        $resolver->setDefaults( array(
            'method'          =>'POST',
            'queryParam'      =>'q',
            'minChars'        => 2,
            'prePopulate'     => null,
            'hintText'        => 'Enter a search term',
            'noResultsText'   => 'No results',
            'searchingText'   => 'Searching'
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $opts = array();

        foreach(array('method', 'queryParam', 'minChars', 'prePopulate', 'hintText', 'noResultsText', 'searchingText') as $opt)
            $opts[$opt] = $options[$opt];

        if(!isset($options['autocompleteUrl']) && $this->router && isset($options['route']))
            $view->vars['attr']['data-autocompleteUrl'] = $this->router->generate($options['route']);
        else if(isset($options['autocompleteUrl']))
            $view->vars['attr']['data-autocompleteUrl'] = $options['autocompleteUrl'];

        $view->vars['attr']['data-options'] = json_encode($opts);
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'autocompleter';
    }
}
