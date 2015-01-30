<?php

namespace NS\AceBundle\Tests\Form;

use \NS\AceBundle\Form\AutocompleterType;
use \NS\AceBundle\Tests\BaseFormTestType;

/**
 * Description of AutocompleterTypeTest
 *
 * @author gnat
 */
class AutocompleterTypeTest extends BaseFormTestType
{

    public function testFormTypeEntityToJson()
    {
        $entity    = new Entity(1);
        $className = get_class($entity);
        $formData  = array('auto' => '{"id":1,"name":"Does Not Matter"}');
        $routeName = 'routename';
        $router    = $this->getRouter();
        $router->expects($this->once())
            ->method('generate')
            ->with($routeName)
            ->willReturn('/route/name');

        $entityMgr = $this->getEntityManager();
        $entityMgr->expects($this->once())
            ->method('getReference')
            ->with($className, 1)
            ->willReturn($entity);

        $form = $this->factory
            ->createBuilder()
            ->add('auto', new AutocompleterType($entityMgr, $router), array(
                'route'  => $routeName,
                'class'  => $className,
                'method' => 'GET'))
            ->getForm();

        $view = $form->createView();
        $form->submit($formData);
        $data = $form['auto']->getData();

        $this->assertEquals($data, $entity);
        $this->assertArrayHasKey('auto', $view);
        $this->assertArrayHasKey('attr', $view['auto']->vars);
        $this->assertArrayHasKey('data-autocompleteUrl', $view['auto']->vars['attr']);
        $this->assertEquals($view['auto']->vars['attr']['data-autocompleteUrl'], '/route/name');
        $this->assertContains('GET', $view['auto']->vars['attr']['data-options']);
    }

    public function testFormTypeCollectionToJson()
    {
        $entities = array(new Entity(1), new Entity(2));

        $className = get_class($entities[0]);
        $formData  = array('auto' => '[{"id":1,"name":"Does Not Matter"},{"id":2,"name":"Does Not Matter"}]');
        $routeName = 'routename';
        $router    = $this->getRouter();

        $entityMgr = $this->getEntityManager();
        $entityMgr->expects($this->at(0))
            ->method('getReference')
            ->with($className, 1)
            ->willReturn($entities[0]);
        $entityMgr->expects($this->at(1))
            ->method('getReference')
            ->with($className, 2)
            ->willReturn($entities[1]);

        $form = $this->factory
            ->createBuilder()
            ->add('auto', new AutocompleterType($entityMgr, $router), array(
                'autocompleteUrl' => $routeName,
                'class'    => $className,
                'multiple' => true))
            ->getForm();

        $view = $form->createView();
        $form->submit($formData);
        $data = $form['auto']->getData();

        $this->assertEquals($data, $entities);
        $this->assertArrayHasKey('auto', $view);
        $this->assertArrayHasKey('attr', $view['auto']->vars);
        $this->assertArrayHasKey('data-autocompleteUrl', $view['auto']->vars['attr']);
        $this->assertEquals($view['auto']->vars['attr']['data-autocompleteUrl'], $routeName);
    }

    private function getRouter()
    {
        return $this->getMockBuilder('\Symfony\Component\Routing\RouterInterface')
                ->disableOriginalConstructor()
                ->setMethods(array('generate', 'match', 'getRouteCollection', 'setContext',
                    'getContext'))
                ->getMock()
        ;
    }

    private function getEntityManager()
    {
        return $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
                ->disableOriginalConstructor()
                ->setMethods(array('getReference', 'find', 'persist', 'merge', 'clear',
                    'detach', 'refresh', 'flush', 'getRepository', 'remove', 'getClassMetadata',
                    'getMetadataFactory', 'initializeObject', 'contains'))
                ->getMock();
    }
}