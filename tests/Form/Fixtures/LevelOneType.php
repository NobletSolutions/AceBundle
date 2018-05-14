<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 14/05/18
 * Time: 2:05 PM
 */

namespace NS\AceBundle\Tests\Form\Fixtures;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class LevelOneType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('field1',TextType::class)
            ->add('field2',LevelTwoConfigType::class,['hidden'=>['parent'=>'field1','value'=>'one']]);
    }
}
