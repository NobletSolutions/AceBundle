<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 15/08/17
 * Time: 12:32 PM
 */

namespace NS\AceBundle\Tests\Form\Extensions;

use NS\AceBundle\Form\Extensions\HiddenParentChildExtension;
use NS\AceBundle\Tests\Form\Fixtures\DeeperHiddenPrototypeConfigType;
use NS\AceBundle\Tests\Form\Fixtures\UsingHiddenConfigType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
        if(method_exists($this,'expectException')) {
            $this->expectException('Symfony\Component\OptionsResolver\Exception\InvalidOptionsException');
        } else {
            $this->setExpectedException('Symfony\Component\OptionsResolver\Exception\InvalidOptionsException');
        }        $builder = $this->factory->createBuilder();
        $builder
            ->add('text', TextType::class)
            ->add('number', NumberType::class)
            ->add('textarea', TextareaType::class, ['hidden' => []]);

        $builder->getForm()->createView();
    }

    public function testHiddenIsNotArray()
    {
        if(method_exists($this,'expectException')) {
            $this->expectException('Symfony\Component\OptionsResolver\Exception\InvalidOptionsException');
        } else {
            $this->setExpectedException('Symfony\Component\OptionsResolver\Exception\InvalidOptionsException');
        }        $builder = $this->factory->createBuilder();
        $builder
            ->add('text', TextType::class)
            ->add('number', NumberType::class)
            ->add('textarea', TextareaType::class, ['hidden' => 'number']);

        $builder->getForm()->createView();
    }

    public function testHiddenHasNoValueForParent()
    {
        if(method_exists($this,'expectException')) {
            $this->expectException('Symfony\Component\OptionsResolver\Exception\InvalidOptionsException');
        } else {
            $this->setExpectedException('Symfony\Component\OptionsResolver\Exception\InvalidOptionsException');
        }

        $builder = $this->factory->createBuilder();
        $builder
            ->add('text', TextType::class)
            ->add('number', NumberType::class)
            ->add('textarea', TextareaType::class, ['hidden' => ['parent'=>'number','values'=>2]]);

        $builder->getForm()->createView();
    }

    public function testPrototypeOneLevelConfig()
    {
        $builder = $this->factory->createBuilder();
        $builder->add('stuff',CollectionType::class,[
            'allow_add'=>true,
            'prototype'=>true,
            'entry_type' => UsingHiddenConfigType::class
        ]);

        $form = $builder->getForm();
        $view = $form->createView();
        $this->assertArrayHasKey('data-context-prototypes', $view->vars['attr']);
        $this->assertEquals('{"form[stuff][__name__][text]":[{"display":"#something","value":"value"}],"form[stuff][__name__][number]":[{"display":["form[stuff][__name__][textarea]"],"values":[1]}]}', $view->vars['attr']['data-context-prototypes']);
    }

    public function testPrototypeDeeperLevelConfig()
    {
        $builder = $this->factory->createBuilder();
        $builder->add('deeper',DeeperHiddenPrototypeConfigType::class);

        $form = $builder->getForm();
        $view = $form->createView();
        $this->assertArrayHasKey('data-context-prototypes', $view->vars['attr']);
        $this->assertEquals('{"form[deeper][something][__name__][text]":[{"display":"#something","value":"value"}],"form[deeper][something][__name__][number]":[{"display":["form[deeper][something][__name__][textarea]"],"values":[1]}]}', $view->vars['attr']['data-context-prototypes']);
    }

    protected function getExtensions()
    {
        $hiddenParent = new HiddenParentChildExtension();
        return [new PreloadedExtension([], [$hiddenParent->getExtendedType() => [$hiddenParent]])];
    }
}
