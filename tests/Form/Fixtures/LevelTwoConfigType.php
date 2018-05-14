<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 14/05/18
 * Time: 2:07 PM
 */

namespace NS\AceBundle\Tests\Form\Fixtures;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class LevelTwoConfigType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('l2Field1', ChoiceType::class,['choices' => ['c1'=>'c1','c2'=>'c2'],'placeholder'=>'place'])
            ->add('l2Field2', TextType::class, ['hidden'=>['parent'=>'l2Field1','value'=>'c2']])
            ->adD('l2Field3', EntityType::class);
    }
}
