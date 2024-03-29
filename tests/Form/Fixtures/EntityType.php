<?php

namespace NS\AceBundle\Tests\Form\Fixtures;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use NS\AceBundle\Tests\Form\Fixtures\Entity;

class EntityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id')
            ->add('name');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entity::class
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'EntityType';
    }
}
