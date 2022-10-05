<?php

namespace NS\AceBundle\Tests\Form;

use Doctrine\ORM\EntityManagerInterface;
use \NS\AceBundle\Form\Transformer\CollectionToJson;
use \NS\AceBundle\Form\Transformer\EntityToJson;
use \NS\AceBundle\Tests\Form\Fixtures\Entity;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TransformerTest extends TestCase
{
    /** @var EntityManagerInterface|MockObject */
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
    }

    public function testCollectionToJsonReverseTransform(): void
    {
        $classes[] = new Entity(1, 'name');
        $classes[] = new Entity(2, 'name');
        $classes[] = new Entity(3, 'name');
        $className = get_class($classes[0]);

        $map = [
            [$className, '1', $classes[0]],
            [$className, '2', $classes[1]],
            [$className, '3', $classes[2]],
        ];

        // Configure the stub.
        $this->entityManager
            ->expects($this->any())
            ->method('getReference')
            ->willReturnMap($map);

        $jsonStr     = sprintf("%d,%d,%d", $classes[0]->getId(), $classes[1]->getId(), $classes[2]->getId());
        $transformer = new CollectionToJson($this->entityManager, $className);
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
        $classes[] = new Entity(1, 'name');
        $classes[] = new Entity(2, 'name');
        $classes[] = new Entity(3, 'name');
        $className = get_class($classes[0]);

        $jsonStr = json_encode([
            ['id' => $classes[0]->getId(), 'name' => 'Does Not Matter'],
            ['id' => $classes[1]->getId(), 'name' => 'Does Not Matter'],
            ['id' => $classes[2]->getId(), 'name' => 'Does Not Matter'],
        ]);

        $transformer = new CollectionToJson($this->entityManager, $className);
        $obj         = $transformer->transform($classes);

        $this->assertEquals($jsonStr, $obj);
    }

    public function testEntityToJsonReverseTransform(): void
    {
        $class     = new Entity(1, 'name');
        $className = get_class($class);

        $this->entityManager->expects($this->once())
            ->method('getReference')
            ->with($className, 1)
            ->willReturn($class);

        $transformer = new EntityToJson($this->entityManager, $className);
        $obj         = $transformer->reverseTransform($class->getId());

        $this->assertEquals($class, $obj);
    }

    public function testEntityToJsonTransform(): void
    {
        $jsonStr     = '[{"id":1,"name":"Does Not Matter"}]';
        $class       = new Entity(1, 'name');
        $transformer = new EntityToJson($this->entityManager, Entity::class);
        $obj         = $transformer->transform($class);

        $this->assertEquals($jsonStr, $obj);
    }

    public function testCollectionToJsonTransformCustomMethod(): void
    {
        $classes[] = new Entity(1, 'name');
        $classes[] = new Entity(2, 'name');
        $classes[] = new Entity(3, 'name');
        $className = get_class($classes[0]);

        $jsonStr = json_encode([
            ['id' => $classes[0]->getId(), 'name' => 'It Matters'],
            ['id' => $classes[1]->getId(), 'name' => 'It Matters'],
            ['id' => $classes[2]->getId(), 'name' => 'It Matters'],
        ]);

        $transformer = new CollectionToJson($this->entityManager, $className, 'someProperty');
        $obj         = $transformer->transform($classes);

        $this->assertEquals($jsonStr, $obj);
    }

    public function testEntityToJsonTransformCustomMethod(): void
    {
        $jsonStr = '[{"id":1,"name":"It Matters"}]';
        $class   = new Entity(1, 'name');

        $transformer = new EntityToJson($this->entityManager, Entity::class, 'someProperty');
        $obj         = $transformer->transform($class);

        $this->assertEquals($jsonStr, $obj);
    }
}
