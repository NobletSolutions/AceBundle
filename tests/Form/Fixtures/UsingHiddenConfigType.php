<?php

namespace NS\AceBundle\Tests\Form\Fixtures;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class UsingHiddenConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextType::class, ['hidden' => ['children' => ['#something' => 'value']]])
            ->add('number', NumberType::class)
            ->add('textarea', TextareaType::class, ['hidden' => ['parent' => 'number', 'value' => 1]]);
    }
}
