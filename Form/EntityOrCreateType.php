<?php

namespace NS\AceBundle\Form;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\OptionsResolver\OptionsResolverInterface;
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
        $entityOptions = isset($options['entity_options']) ?
            array_merge($options['entity_options'], array('class' => $options['class'])) : array('class' => $options['class']);

        $builder->add('finder', 'autocompleter', $entityOptions);

        if ($options['include_form'])
            $builder->add('createForm', $options['type'], isset($options['create_options']) ? $options['create_options'] : array());

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
    }

    /**
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array('class', 'type'));
        $resolver->setOptional(array('entity_options', 'create_options'));
        $resolver->setDefaults(array(
            'include_button' => true,
            'include_form'   => true
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'entity_create';
    }
}