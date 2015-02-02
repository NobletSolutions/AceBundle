<?php

namespace NS\AceBundle\Tests\Form\Fixtures;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of EntityType
 *
 * @author gnat
 */
class EntityType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id')
            ->add('name')
        ;
    }

    public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'NS\AceBundle\Tests\Form\Fixtures\Entity'));
    }

    public function getName()
    {
        return 'EntityType';
    }
}