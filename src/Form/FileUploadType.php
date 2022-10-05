<?php

namespace NS\AceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileUploadType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'uploadUrl' => false,
            'viewUrl'   => false,
            'attr'      => ['class' => 'nsFileUpload'],
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if ($options['uploadUrl']) {
            $view->vars['attr']['data-uploadurl'] = $options['uploadUrl'];
        }

        if ($options['viewUrl']) {
            $view->vars['attr']['data-viewurl'] = $options['viewUrl'];
        }
    }

    public function getParent(): string
    {
        return FileType::class;
    }
}
