<?php

namespace NS\AceBundle\Tests\Form;

use Doctrine\ORM\EntityManagerInterface;
use \NS\AceBundle\Form\Transformer\CollectionToJson;
use \NS\AceBundle\Form\Transformer\EntityToJson;
use \NS\AceBundle\Tests\Form\Fixtures\Entity;
use PHPUnit\Framework\TestCase;

class TransformerTest extends TestCase
{
    public function testCollectionToJsonReverseTransform(): void
    {
        $classes[] = new Entity(1);
        $classes[] = new Entity(2);
        $classes[] = new Entity(3);
        $className = get_class($classes[0]);

        $entityMgrMock = $this->getEntityManager();

        $map = [
            [$className, '1', $classes[0]],
            [$className, '2', $classes[1]],
            [$className, '3', $classes[2]],
        ];

        // Configure the stub.
        $entityMgrMock
            ->expects($this->any())
            ->method('getReference')
            ->willReturnMap($map);

        $jsonStr     = sprintf("%d,%d,%d", $classes[0]->getId(), $classes[1]->getId(), $classes[2]->getId());
        $transformer = new CollectionToJson($entityMgrMock, $className);
        $obj         = $transformer->reverseTransform($jsonStr);

        $this->assertIsArray($obj);
        $this->assertArrayHasKey(0, $obj);
        $this->assertIsObject($obj[0]);
        $this->assertEquals($classes[0], $obj[0]);
        $this->assertEquals($classes[1], $obj[1]);
        $this->assertEquals($classes[2], $obj[2]);
    }

    public function testCollectionToJsonTransform(): void
    {
        $classes[] = new Entity(1);
        $classes[] = new Entity(2);
        $classes[] = new Entity(3);
        $className = get_class($classes[0]);

        $entityMgrMock = $this->getEntityManager();

        $jsonStr = json_encode([
            ['id' => $classes[0]->getId(), 'name' => 'Does Not Matter'],
            ['id' => $classes[1]->getId(), 'name' => 'Does Not Matter'],
            ['id' => $classes[2]->getId(), 'name' => 'Does Not Matter'],
        ]);

        $transformer = new CollectionToJson($entityMgrMock, $className);
        $obj         = $transformer->transform($classes);

        $this->assertEquals($jsonStr, $obj);
    }

    public function testEntityToJsonReverseTransform(): void
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

    public function testEntityToJsonTransform(): void
    {
        $jsonStr = '[{"id":1,"name":"Does Not Matter"}]';
        $class   = new Entity(1);

        $entityMgrMock = $this->getEntityManager();
        $transformer   = new EntityToJson($entityMgrMock, Entity::class);
        $obj           = $transformer->transform($class);

        $this->assertEquals($jsonStr, $obj);
    }

    public function testCollectionToJsonTransformCustomMethod(): void
    {
        $classes[] = new Entity(1);
        $classes[] = new Entity(2);
        $classes[] = new Entity(3);
        $className = get_class($classes[0]);

        $entityMgrMock = $this->getEntityManager();

        $jsonStr = json_encode([
            ['id' => $classes[0]->getId(), 'name' => 'It Matters'],
            ['id' => $classes[1]->getId(), 'name' => 'It Matters'],
            ['id' => $classes[2]->getId(), 'name' => 'It Matters'],
        ]);

        $transformer = new CollectionToJson($entityMgrMock, $className, 'someProperty');
        $obj         = $transformer->transform($classes);

        $this->assertEquals($jsonStr, $obj);
    }

    public function testEntityToJsonTransformCustomMethod(): void
    {
        $jsonStr = '[{"id":1,"name":"It Matters"}]';
        $class   = new Entity(1);

        $entityMgrMock = $this->getEntityManager();
        $transformer   = new EntityToJson($entityMgrMock, Entity::class, 'someProperty');
        $obj           = $transformer->transform($class);

        $this->assertEquals($jsonStr, $obj);
    }

    private function getEntityManager()
    {
        return $this->createMock(EntityManagerInterface::class);
    }
}
