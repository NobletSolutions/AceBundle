<?php

namespace NS\AceBundle\Tests\Form;

use Doctrine\ORM\EntityManagerInterface;
use NS\AceBundle\Form\AutocompleterType;
use NS\AceBundle\Tests\BaseFormTestType;
use NS\AceBundle\Tests\Form\Fixtures\Entity;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\Routing\RouterInterface;

/**
 * Description of AutocompleterTypeTest
 *
 * @author gnat
 */
class AutocompleterTypeTest extends BaseFormTestType
{
    /** @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $entityMgr;

    /** @var  RouterInterface */
    private $router;

    protected function setUp()
    {
        $this->router = $this->createMock(RouterInterface::class);

        $this->entityMgr = $this->createMock(EntityManagerInterface::class);

        parent::setUp();
    }

    protected function getExtensions()
    {
        $type = new AutocompleterType($this->entityMgr, $this->router);

        return [new PreloadedExtension([$type],[])];
    }

    public function testFormTypeEntityToJson()
    {
        $entity    = new Entity(1);
        $formData  = ['auto' => '1'];
        $routeName = 'routename';
        $this->router->expects($this->once())
            ->method('generate')
            ->with($routeName)
            ->willReturn('/route/name');

        $this->entityMgr->expects($this->once())
            ->method('getReference')
            ->with(Entity::class, 1)
            ->willReturn($entity);

        $form = $this->factory
            ->createBuilder()
            ->add('auto', AutocompleterType::class, [
                'route'  => $routeName,
                'class'  => Entity::class,
                'data_class' => Entity::class,
                'method' => 'GET'])
            ->getForm();

        $view = $form->createView();
        $form->submit($formData);
        $data = $form['auto']->getData();

        $this->assertEquals($data, $entity);
        $this->assertInstanceOf(Entity::class, $data);
        $this->assertArrayHasKey('auto', $view);
        $this->assertArrayHasKey('attr', $view['auto']->vars);
        $this->assertArrayHasKey('data-autocompleteUrl', $view['auto']->vars['attr']);
        $this->assertEquals($view['auto']->vars['attr']['data-autocompleteUrl'], '/route/name');
        $this->assertContains('GET', $view['auto']->vars['attr']['data-options']);
    }

    /**
     * @group collection
     */
    public function testFormTypeCollectionToJson()
    {
        $entities  = [new Entity(1), new Entity(2)];

        $this->entityMgr
            ->expects($this->at(0))
            ->method('getReference')
            ->with(Entity::class,1)
            ->willReturn($entities[0]);

        $this->entityMgr
            ->expects($this->at(1))
            ->method('getReference')
            ->with(Entity::class,2)
            ->willReturn($entities[1]);

        $form = $this->factory
            ->createBuilder()
            ->add('auto', AutocompleterType::class, [
                'autocompleteUrl' => 'routename',
                'class'           => Entity::class,
                'multiple'        => true])
            ->getForm();

        $form->submit(['auto' => '1,2']);
        $data = $form['auto']->getData();

        $this->assertEquals($entities, $data);

        $view = $form->createView();
        $this->assertArrayHasKey('auto', $view);
        $this->assertArrayHasKey('attr', $view['auto']->vars);
        $this->assertArrayHasKey('data-autocompleteUrl', $view['auto']->vars['attr']);
        $this->assertEquals('routename', $view['auto']->vars['attr']['data-autocompleteUrl']);
    }

    /**
     * @group customTransformer
     */
    public function testFormTypeCustomTransformer()
    {
        $entities = [new Entity(1), new Entity(2)];

        $formData  = ['auto' => '1,2'];
        $routeName = 'routename';
        $jsonStr     = sprintf("%d,%d", $entities[0]->getId(), $entities[1]->getId());

        $customTransformer = $this->createMock(DataTransformerInterface::class);
        $customTransformer->expects($this->any())
            ->method('transform')
            ->willReturn($jsonStr);
        $customTransformer->expects($this->any())
            ->method('reverseTransform')
            ->willReturn($entities);

        $form = $this->factory
            ->createBuilder()
            ->add('auto', AutocompleterType::class, [
                'autocompleteUrl' => $routeName,
                'class'           => Entity::class,
                'multiple'        => true,
                'transformer'     => $customTransformer])
            ->getForm();

        $view = $form->createView();
        $form->submit($formData);
        $data = $form['auto']->getData();

//        $this->assertEquals($entities, $data);
        $this->assertArrayHasKey('auto', $view);
        $this->assertArrayHasKey('attr', $view['auto']->vars);
        $this->assertArrayHasKey('data-autocompleteUrl', $view['auto']->vars['attr']);
        $this->assertEquals($view['auto']->vars['attr']['data-autocompleteUrl'], $routeName);
    }

    public function testFormWithDataCustomProperty()
    {
        $entity     = new Entity(1);
        $properties = [
            'property' => 'someProperty',
            'class' => Entity::class,
        ];

        $form = $this->factory
            ->createBuilder()
            ->add('auto', AutocompleterType::class, $properties)
            ->getForm();

        $viewData = $form->getViewData();
        $this->assertNull($viewData);
        $form->setData(['auto' => $entity]);
        $view = $form->createView();
        $viewData = $form->getViewData();
        $this->assertNotNull($viewData);

        $this->assertEquals('[{"id":1,"name":"It Matters"}]', $view['auto']->vars['value']);
    }

    public function testFormWithDataToString()
    {
        $entity    = new Entity(1);

        $form = $this->factory
            ->createBuilder()
            ->add('auto', AutocompleterType::class, ['class' => Entity::class,])
            ->getForm();

        $viewData = $form->getViewData();
        $this->assertNull($viewData);
        $form->setData(['auto' => $entity]);
        $view     = $form->createView();
        $this->assertEquals('[{"id":1,"name":"Does Not Matter"}]', $view['auto']->vars['value']);
    }

    public function testFormWithNullData()
    {
        $this->entityMgr
            ->expects($this->never())
            ->method('getReference');

        $form = $this->factory
            ->createBuilder()
            ->add('auto', AutocompleterType::class, ['class' => Entity::class,])
            ->getForm();

        $form->submit([]);
        $data = $form['auto']->getData();
        $this->assertNull($data);
    }

    public function testMultipleWithDataClassIsException()
    {
        if(method_exists($this,'expectException')) {
            $this->expectException(InvalidOptionsException::class);
        } else {
            $this->setExpectedException(InvalidOptionsException::class);
        }

        $this->factory->create(AutocompleterType::class,null,['multiple'=>true,'data_class'=>Entity::class]);
    }
}
