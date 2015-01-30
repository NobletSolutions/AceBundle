<?php

namespace NS\AceBundle\Tests\Form;

use \NS\AceBundle\Form\Transformer\CollectionToJson;
use \NS\AceBundle\Form\Transformer\EntityToJson;

/**
 * Description of TransformerTest
 *
 * @author gnat
 */
class TransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testCollectionToJsonReverseTransform()
    {
        $classes[] = new Entity(1);
        $classes[] = new Entity(2);
        $classes[] = new Entity(3);
        $className = get_class($classes[0]);

        $entityMgrMock = $this->getEntityManager();

        $map = array(
            array($className, 1, $classes[0]),
            array($className, 2, $classes[1]),
            array($className, 3, $classes[2]),
        );

        // Configure the stub.
        $entityMgrMock
            ->expects($this->any())
            ->method('getReference')
            ->will($this->returnValueMap($map));

        $jsonStr = json_encode(array(
            array('id' => $classes[0]->getId(), 'name' => 'Does Not Matter'),
            array('id' => $classes[1]->getId(), 'name' => 'Does Not Matter'),
            array('id' => $classes[2]->getId(), 'name' => 'Does Not Matter'),
        ));

        $transformer = new CollectionToJson($entityMgrMock, $className);
        $obj         = $transformer->reverseTransform($jsonStr);

        $this->assertTrue(is_array($obj));
        $this->assertArrayHasKey(0, $obj);
        $this->assertTrue(is_object($obj[0]));
        $this->assertEquals($classes[0], $obj[0]);
        $this->assertEquals($classes[1], $obj[1]);
        $this->assertEquals($classes[2], $obj[2]);
    }

    /**
     *
     */
    public function testCollectionToJsonTransform()
    {
        $classes[] = new Entity(1);
        $classes[] = new Entity(2);
        $classes[] = new Entity(3);
        $className = get_class($classes[0]);

        $entityMgrMock = $this->getEntityManager();

        $jsonStr = json_encode(array(
            array('id' => $classes[0]->getId(), 'name' => 'Does Not Matter'),
            array('id' => $classes[1]->getId(), 'name' => 'Does Not Matter'),
            array('id' => $classes[2]->getId(), 'name' => 'Does Not Matter'),
        ));

        $transformer = new CollectionToJson($entityMgrMock, $className);
        $obj         = $transformer->transform($classes);

        $this->assertEquals($jsonStr, $obj);
    }

    /**
     *
     */
    public function testEntityToJsonReverseTransform()
    {
        $class     = new Entity(1);
        $className = get_class($class);

        $entityMgrMock = $this->getEntityManager();
        $entityMgrMock->expects($this->once())
            ->method('getReference')
            ->with($className, 1)
            ->willReturn($class);

        $jsonStr     = json_encode(array('id' => $class->getId(), 'name' => 'Does Not Matter'));
        $transformer = new EntityToJson($entityMgrMock, $className);
        $obj         = $transformer->reverseTransform($jsonStr);

        $this->assertEquals($class, $obj);
    }

    /**
     *
     */
    public function testEntityToJsonTransform()
    {
        $jsonStr = '{"id":1,"name":"Does Not Matter"}';
        $class   = new Entity(1);

        $entityMgrMock = $this->getEntityManager();
        $transformer   = new EntityToJson($entityMgrMock, 'NS\AceBundle\Tests\Form\Entity');
        $obj           = $transformer->transform($class);

        $this->assertEquals($jsonStr, $obj);
    }

    /**
     *
     */
    private function getEntityManager()
    {
        return $this->getMockBuilder('Doctrine\ORM\EntityManager')
                ->disableOriginalConstructor()
                ->getMock();
    }
}