<?php

declare(strict_types=1);

namespace Consultorios\ORM;

use Consultorios\ORM\Fixtures\EntityDummy;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @internal Test class
 */
final class ObjectRepositoryInMemoryTest extends TestCase
{
    /**
     * @var int
     */
    private const ENTITIES_COUNT = 10;

    public function testFindWithMatch(): void
    {
        $scalarId = $this->createUUID();
        $entitySaved = $this->createEntity($scalarId);

        $repository = $this->repositoy([$entitySaved]);

        $entity = $repository->find($scalarId);

        $this->assertSame($entitySaved, $entity);
    }

    public function testFindWitouthMatch(): void
    {
        $entity = $this->repositoy()->find($this->createUUID());

        $this->assertNull($entity);
    }

    public function testFindAll(): void
    {
        $repository = $this->repositoy();

        $entities = $repository->findAll();

        $this->assertCount(self::ENTITIES_COUNT, $entities);
        $this->assertContainsOnlyInstancesOf(EntityDummy::class, $entities);
    }

    public function testFindBy(): void
    {
        $property = 'asd';

        $entity1 = $this->createEntity(null, $property);
        $entity2 = $this->createEntity(null, $property);

        $entitiesList = [
            $entity1,
            $entity2,
        ];

        $repository = $this->repositoy($entitiesList);

        $entities = $repository->findBy([
            'property' => $property,
        ]);

        $this->assertContainsOnlyInstancesOf(EntityDummy::class, $entities);
        $this->assertCount(count($entitiesList), $entities);
        $this->assertContains($entity1, $entities);
        $this->assertContains($entity2, $entities);
    }

    public function testFindOneBy(): void
    {
        $property = 'asd';

        $entitySaved = $this->createEntity(null, $property);

        $repository = $this->repositoy([$entitySaved]);

        $entity = $repository->findOneBy([
            'property' => $property,
        ]);

        $this->assertSame($entitySaved, $entity);
    }

    public function testGetClassName(): void
    {
        $this->assertSame(
            EntityDummy::class,
            $this->repositoy()->getClassName()
        );
    }

    /**
     * @param EntityDummy[] $entities
     *
     * @psalm-return ObjectRepositoryInMemory<EntityDummy>
     */
    private function repositoy(array $entities = []): ObjectRepositoryInMemory
    {
        $entitiesCount = count($entities);
        for ($i = 0; $i < self::ENTITIES_COUNT - $entitiesCount; ++$i) {
            $entities[] = $this->createEntity();
        }

        \shuffle($entities);

        return new ObjectRepositoryInMemory($entities, EntityDummy::class);
    }

    private function createEntity(?string $id = null, ?string $property = null): EntityDummy
    {
        return new EntityDummy(
            $id ?? $this->createUUID(),
            $property ?? ''
        );
    }

    private function createUUID(): string
    {
        return (string) Uuid::uuid4();
    }
}
