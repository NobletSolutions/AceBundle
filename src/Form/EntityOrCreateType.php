<?php

namespace NS\AceBundle\Form;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\OptionsResolver\OptionsResolver;
use \Symfony\Component\Form\FormView;
use \Symfony\Component\Form\FormInterface;
use \NS\AceBundle\Form\Transformer\EntityOrCreate;

/**
 * Description of EntityOrCreateType
 *
 * @author gnat
 */
class EntityOrCreateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entityOptions = array_merge($options['entity_options'], array('class' => $options['class']));

        $builder->add('finder', 'NS\AceBundle\Form\AutocompleterType', $entityOptions);

        if ($options['include_form']) {
            $builder->add('createForm', $options['type'], isset($options['create_options']) ? $options['create_options'] : array());
        }

        $builder->addModelTransformer(new EntityOrCreate());
    }

    /**
     *
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['include_button'] = $options['include_button'];
        $view->vars['include_form']   = $options['include_form'];

        if (isset($options['modal_size'])) {
            $view->vars['modal_size'] = sprintf("modal-%d", $options['modal_size']);
        }
    }

    /**
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array('class', 'type'));
        $resolver->setDefined(array('create_options', 'modal_size'));
        $resolver->setDefaults(array(
            'include_button' => true,
            'include_form'   => true,
            'error_bubbling' => false,
            'entity_options' => array(),
        ));

        $resolver->setAllowedValues('modal_size', array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'entity_create';
    }
}
