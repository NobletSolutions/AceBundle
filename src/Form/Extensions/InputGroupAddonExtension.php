<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 31/05/17
 * Time: 4:27 PM
 */

namespace NS\AceBundle\Form\Extensions;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InputGroupAddonExtension extends AbstractTypeExtension
{
    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(['input-addon-left','input-addon-right']);
    }

    /**
     * @inheritDoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (isset($options['input-addon-left'])) {
            $view->vars['addon_left'] = $options['input-addon-left'];
        }

        if (isset($options['input-addon-right'])) {
            $view->vars['addon_right'] = $options['input-addon-right'];
        }
    }

    /**
     * @inheritDoc
     */
    public function getExtendedType()
    {
        return FormType::class;
    }
}
