<?php

namespace NS\AceBundle\Form;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\OptionsResolver\OptionsResolver;
use \Symfony\Component\Form\ButtonTypeInterface;
use \Symfony\Component\Form\FormView;
use \Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of IconButtonType
 *
 * @author gnat
 */
class IconButtonType extends AbstractType implements ButtonTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        if ($resolver instanceof OptionsResolverInterface) {
            $resolver->setOptional(array('icon', 'type'));
        } else {
            $resolver->setDefined(array('icon', 'type'));
        }

        $resolver->setAllowedTypes(array('icon' => 'string'));
        $resolver->setAllowedValues(array('type' => array('button', 'submit', 'reset')));
    }

    /**
     * @inheritDoc
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (isset($options['icon'])) {
            $view->vars['icon'] = $options['icon'];
        }

        if (isset($options['type'])) {
            $view->vars['type'] = $options['type'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'button';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'iconbutton';
    }
}
