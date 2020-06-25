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

    public function testFormType(): void
    {
        $entity          = new Entity(1);
        $this->entityMgr->expects($this->once())
            ->method('getReference')
            ->willReturn($entity);

        $className = Entity::class;
        $formData = [
            'entity' => ['finder' => 1,'createFormClicked'=>'finder'],
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

    public function testFormType2(): void
    {
        $this->entityMgr->expects($this->never())->method('getReference');

        $className = Entity::class;
        $formData  = [
            'entity' => [
                'finder' => '',
                'createFormClicked'=> 'create',
                'createForm' => ['id' => 2, 'name' => 'Other Name'],],
        ];

        $formProperties = [
            'type'  => EntityType::class,
            'class' => $className,
        ];
        
        $form = $this->factory->createBuilder()
            ->add('entity', EntityOrCreateType::class, $formProperties)
            ->getForm();

        $this->assertArrayHasKey('finder', $form['entity']);
        $this->assertArrayHasKey('createForm', $form['entity']);
        $this->assertArrayHasKey('createFormClicked', $form['entity']);

        $form->submit($formData);

        $data = $form['entity']->getData();
        $this->assertInstanceOf($className, $data);
        $this->assertEquals(2, $data->getId());
        $this->assertEquals('Other Name', $data->getName());
    }

    /**
     * @group multiple
     */
    public function testMultipleFinder(): void
    {
        $firstEntity  = new Entity(1);
        $secondEntity = new Entity(2);

        $this->entityMgr
            ->method('getReference')
            ->willReturnCallback(static function ($class, int $id) use ($firstEntity, $secondEntity) {
                if ($class === Entity::class) {
                    if ($id === $firstEntity->getId()) {
                        return $firstEntity;
                    }
                    if ($id === $secondEntity->getId()) {
                        return $secondEntity;
                    }
                }

                throw new \RuntimeException("Class:$class, $id");
            });

        $formData = [
            'entity' => [
                'finder' => '1,2',
                'createFormClicked'=>'finder',
                ],
        ];

        $formOptions = [
            'type'  => EntityType::class,
            'class' => Entity::class,
            'entity_options' => ['multiple' => true],
        ];

        $form = $this->factory->createBuilder()
            ->add('entity', EntityOrCreateType::class, $formOptions)
            ->getForm();

        $this->assertTrue($form['entity']->has('finder'));
        $this->assertTrue($form['entity']->has('createForm'));

        $form->submit($formData);

        $data = $form['entity']->getData();
        $this->assertIsArray($data);
        $this->assertEquals(1, $data[0]->getId());
    }

    public function testMultipleCreate(): void
    {
        $this->entityMgr
            ->expects($this->never())
            ->method('getReference');

        $formData = [
            'entity' => [
                'finder' => '',
                'createForm' => ['id' => 'theId', 'name' => 'theName'],
                'createFormClicked' => 'create',
            ],
        ];

        $formOptions = [
            'type'  => EntityType::class,
            'class' => Entity::class,
            'entity_options' => ['multiple' => true],
        ];

        $form = $this->factory->createBuilder()
            ->add('entity', EntityOrCreateType::class, $formOptions)
            ->getForm();

        $this->assertTrue($form['entity']->has('finder'));
        $this->assertTrue($form['entity']->has('createForm'));

        $form->submit($formData);

        $data = $form['entity']->getData();
        $this->assertIsArray($data);
        $this->assertEquals('theId', $data[0]->getId());
    }

    public function getExtensions(): array
    {
        $this->entityMgr = $this->createMock(EntityManagerInterface::class);
        $type = new AutocompleterType($this->entityMgr);

        return [new PreloadedExtension([$type], [])];
    }
}
