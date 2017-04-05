<?php

namespace NS\AceBundle\Form;

use \Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
        $resolver->setDefaults([
            'uploadUrl' => false,
            'viewUrl'   => false,
            'attr'      => ['class' => 'nsFileUpload'],
        ]);
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if ($options['uploadUrl']) {
            $view->vars['attr']['data-uploadurl'] = $options['uploadUrl'];
        }

        if ($options['viewUrl']) {
            $view->vars['attr']['data-viewurl'] = $options['viewUrl'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return FileType::class;
    }
}
