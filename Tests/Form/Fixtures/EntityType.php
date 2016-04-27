<?php

namespace NS\AceBundle\Tests\Form\Fixtures;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\AceBundle\Tests\Form\Fixtures\Entity'
        ));
    }

    public function getBlockPrefix()
    {
        return 'EntityType';
    }
}
