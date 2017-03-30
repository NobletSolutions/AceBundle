<?php

namespace NS\AceBundle\Tests\Form;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use \NS\AceBundle\Form\Transformer\CollectionToJson;
use \NS\AceBundle\Form\Transformer\EntityToJson;
use NS\AceBundle\Tests\BaseTestCase;
use \NS\AceBundle\Tests\Form\Fixtures\Entity;

/**
 * Description of TransformerTest
 *
 * @author gnat
 */
class TransformerTest extends BaseTestCase
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
            array($className, '1', $classes[0]),
            array($className, '2', $classes[1]),
            array($className, '3', $classes[2]),
        );

        // Configure the stub.
        $entityMgrMock
            ->expects($this->any())
            ->method('getReference')
            ->will($this->returnValueMap($map));

        $jsonStr     = sprintf("%d,%d,%d", $classes[0]->getId(), $classes[1]->getId(), $classes[2]->getId());
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

        $transformer = new EntityToJson($entityMgrMock, $className);
        $obj         = $transformer->reverseTransform($class->getId());

        $this->assertEquals($class, $obj);
    }

    /**
     *
     */
    public function testEntityToJsonTransform()
    {
        $jsonStr = '[{"id":1,"name":"Does Not Matter"}]';
        $class   = new Entity(1);

        $entityMgrMock = $this->getEntityManager();
        $transformer   = new EntityToJson($entityMgrMock, 'NS\AceBundle\Tests\Form\Fixtures\Entity');
        $obj           = $transformer->transform($class);

        $this->assertEquals($jsonStr, $obj);
    }

    public function testCollectionToJsonTransformCustomMethod()
    {
        $classes[] = new Entity(1);
        $classes[] = new Entity(2);
        $classes[] = new Entity(3);
        $className = get_class($classes[0]);

        $entityMgrMock = $this->getEntityManager();

        $jsonStr = json_encode(array(
            array('id' => $classes[0]->getId(), 'name' => 'It Matters'),
            array('id' => $classes[1]->getId(), 'name' => 'It Matters'),
            array('id' => $classes[2]->getId(), 'name' => 'It Matters'),
        ));

        $transformer = new CollectionToJson($entityMgrMock, $className, 'someProperty');
        $obj         = $transformer->transform($classes);

        $this->assertEquals($jsonStr, $obj);
    }

    public function testEntityToJsonTransformCustomMethod()
    {
        $jsonStr = '[{"id":1,"name":"It Matters"}]';
        $class   = new Entity(1);

        $entityMgrMock = $this->getEntityManager();
        $transformer   = new EntityToJson($entityMgrMock, 'NS\AceBundle\Tests\Form\Fixtures\Entity', 'someProperty');
        $obj           = $transformer->transform($class);

        $this->assertEquals($jsonStr, $obj);
    }

    /**
     *
     */
    private function getEntityManager()
    {
        return $this->createMock(EntityManagerInterface::class);
    }
}
