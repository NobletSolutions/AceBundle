<?php

namespace NS\AceBundle\Tests\Form;

use Doctrine\ORM\EntityManagerInterface;
use NS\AceBundle\Form\AutocompleterType;
use NS\AceBundle\Form\EntityOrCreateType;
use NS\AceBundle\Tests\BaseFormTestType;
use NS\AceBundle\Tests\Form\Fixtures\Entity;
use NS\AceBundle\Tests\Form\Fixtures\EntityType;
use Symfony\Component\Form\PreloadedExtension;

/**
 * Description of EntityOrCreateTypeTest
 *
 * @author gnat
 */
class EntityOrCreateTypeTest extends BaseFormTestType
{
    /** @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $entityMgr;

    public function testFormType()
    {
        $entity          = new Entity(1);
        $this->entityMgr->expects($this->once())
            ->method('getReference')
            ->willReturn($entity);

        $className = 'NS\AceBundle\Tests\Form\Fixtures\Entity';
        $formData = [
            'entity' => ['finder' => 1],
        ];

        $formOptions = [
            'type'  => EntityType::class,
            'class' => $className,
        ];

        $form = $this->factory->createBuilder()
            ->add('entity', EntityOrCreateType::class, $formOptions)
            ->getForm();

        $this->assertTrue($form['entity']->has('finder'));
        $this->assertTrue($form['entity']->has('createForm'));

        $form->submit($formData);

        $data = $form['entity']->getData();
        $this->assertInstanceOf($className, $data);
        $this->assertEquals(1, $data->getId());
    }

    public function testFormType2()
    {
        $this->entityMgr->expects($this->never())->method('getReference');

        $className = 'NS\AceBundle\Tests\Form\Fixtures\Entity';
        $formData  = [
            'entity' => ['createForm' => ['id' => 2, 'name' => 'Other Name']],
        ];

        $formProperties = [
            'type'  => EntityType::class,
            'class' => $className,
        ];
        
        $form = $this->factory->createBuilder()
            ->add('entity', EntityOrCreateType::class, $formProperties)
            ->getForm();

        $this->assertCount(2, $form['entity']);
        $this->assertArrayHasKey('finder', $form['entity']);
        $this->assertArrayHasKey('createForm', $form['entity']);

        $form->submit($formData);

        $data = $form['entity']->getData();
        $this->assertInstanceOf($className, $data);
        $this->assertEquals(2, $data->getId());
        $this->assertEquals('Other Name', $data->getName());
    }

    public function getExtensions()
    {
        $this->entityMgr = $this->createMock(EntityManagerInterface::class);
        $type = new AutocompleterType($this->entityMgr);

        return [
            new PreloadedExtension([$type], [])];
    }
}
