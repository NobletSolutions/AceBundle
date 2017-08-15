<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 15/08/17
 * Time: 12:32 PM
 */

namespace NS\AceBundle\Tests\Form\Extensions;

use NS\AceBundle\Form\Extensions\HiddenParentChildExtension;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class HiddenParentChildExtensionTest extends TypeTestCase
{
    public function testNoConfig()
    {
        $builder = $this->factory->createBuilder();
        $builder
            ->add('text', TextType::class)
            ->add('number', NumberType::class)
            ->add('textarea', TextareaType::class);

        $form = $builder->getForm();
        $view = $form->createView();
        $this->assertArrayNotHasKey('data-context-config', $view->vars['attr']);
    }

    public function testHasConfig()
    {
        $builder = $this->factory->createBuilder();
        $builder
            ->add('text', TextType::class)
            ->add('number', NumberType::class)
            ->add('textarea', TextareaType::class, ['hidden' => ['parent' => 'number', 'value' => 1]]);

        $form = $builder->getForm();
        $view = $form->createView();
        $this->assertArrayHasKey('data-context-config', $view->vars['attr']);
    }

    public function testHiddenIsEmpty()
    {
        $this->expectException('Symfony\Component\OptionsResolver\Exception\InvalidOptionsException');
        $builder = $this->factory->createBuilder();
        $builder
            ->add('text', TextType::class)
            ->add('number', NumberType::class)
            ->add('textarea', TextareaType::class, ['hidden' => []]);

        $builder->getForm()->createView();
    }

    public function testHiddenIsNotArray()
    {
        $this->expectException('Symfony\Component\OptionsResolver\Exception\InvalidOptionsException');
        $builder = $this->factory->createBuilder();
        $builder
            ->add('text', TextType::class)
            ->add('number', NumberType::class)
            ->add('textarea', TextareaType::class, ['hidden' => 'number']);

        $builder->getForm()->createView();
    }

    public function testHiddenHasNoValueForParent()
    {
        $this->expectException('Symfony\Component\OptionsResolver\Exception\InvalidOptionsException');
        $builder = $this->factory->createBuilder();
        $builder
            ->add('text', TextType::class)
            ->add('number', NumberType::class)
            ->add('textarea', TextareaType::class, ['hidden' => ['parent'=>'number','values'=>2]]);

        $builder->getForm()->createView();
    }


    protected function getExtensions()
    {
        $hiddenParent = new HiddenParentChildExtension();
        return [new PreloadedExtension([], [$hiddenParent->getExtendedType() => [$hiddenParent]])];
    }
}
