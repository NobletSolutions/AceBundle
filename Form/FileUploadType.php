<?php

namespace NS\AceBundle\Form;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormView;
use \Symfony\Component\Form\FormInterface;
use \Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of SwitchType
 *
 * @author gnat
 */
class FileUploadType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $defaults = array(
            'uploadUrl' => false,
            'viewUrl'   => false,
            'attr'      => array('class' => 'nsFileUpload'),
        );
        $resolver->setDefaults($defaults);
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if($options['uploadUrl']) {
            $view->vars['attr']['data-uploadurl'] = $options['uploadUrl'];
        }

        if($options['viewUrl']) {
            $view->vars['attr']['data-viewurl'] = $options['viewUrl'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'fileupload';
    }
}
