<?php

namespace NS\AceBundle\Form\Extensions;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of ButtonTypeExtension
 *
 * @author gnat
 */
class ButtonTypeExtension extends AbstractTypeExtension
{
    public function getExtendedType()
    {
        return 'submit';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setOptional(array('icon','type'));
        $resolver->setAllowedTypes(array('icon'=>'string'));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if(isset($options['icon']))
            $view->vars['icon'] = $options['icon'];
    }
}