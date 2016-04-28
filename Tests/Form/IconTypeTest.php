<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 28/04/16
 * Time: 9:59 AM
 */

namespace NS\AceBundle\Tests\Form;


use NS\AceBundle\Form\Extensions\ButtonTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

class IconTypeTest extends TypeTestCase
{

    public function testButtonIconOption()
    {
        $formBuilder = $this->factory->createBuilder();
        $formBuilder->add('masked', ButtonType::class,array('icon'=>'fa fa-cog'));
        try {
            $form = $formBuilder->getForm();
            $this->assertInstanceOf('Symfony\Component\Form',$form);
        } catch (UndefinedOptionsException $exception) {
            $this->fail('icon is an unknown option '.$exception->getMessage());
        }
    }

    public function testButtonIconOption()
    {
        $formBuilder = $this->factory->createBuilder();
        $formBuilder->add('masked', SubmitType::class, array('icon'=>'fa fa-cog'));
        try {
            $form = $formBuilder->getForm();
            $this->assertInstanceOf('Symfony\Component\Form',$form);
        } catch (UndefinedOptionsException $exception) {
            $this->fail('icon is an unknown option '.$exception->getMessage());
        }
    }

    protected function getExtensions()
    {
        return array_merge(parent::getExtensions(), array(new ButtonTypeExtension()));
    }
}
