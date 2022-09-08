<?php

declare(strict_types=1);

namespace Consultorios\ORM;

use Doctrine\Persistence\ObjectRepository;
use ReflectionClass;

/**
 * @template T of object
 * @template-implements ObjectRepository<T>
 */
final class ObjectRepositoryInMemory implements ObjectRepository
{
    private readonly ReflectionClass $reflector;

    /**
     * @psalm-external-mutation-free
     * @psalm-param T[] $list
     * @psalm-param class-string<T> $className
     */
    public function __construct(
        private readonly array $list,
        private readonly string $className
    ) {
        $this->reflector = new \ReflectionClass($className);
    }

    /**
     * @codeCoverageIgnore
     */
    public function __clone()
    {
        throw new \Exception('Cloning this class is not allowed');
    }

    /**
     * @codeCoverageIgnore
     */
    public function __sleep()
    {
        throw new \Exception("This class can't be serialized");
    }

    public function find($id): ?object
    {
        foreach ($this->list as $object) {
            if ($this->getPropertyValue($object, 'id') === $id) {
                return $object;
            }
        }

        return null;
    }

    /**
     * @return T[]
     */
    public function findAll(): array
    {
        return $this->list;
    }

    /**
     * @return T[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return \array_filter(
            $this->list,
            /** @psalm-param T $object */
            function (object $object) use ($criteria): bool {
                foreach ($criteria as $key => $value) {
                    if ($value !== $this->getPropertyValue($object, $key)) {
                        return false;
                    }
                }

                return true;
            }
        );
    }

    public function findOneBy(array $criteria): ?object
    {
        $coincidences = $this->findBy($criteria);
        return \reset($coincidences) ?: null;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @psalm-param T $object
     */
    private function getPropertyValue(object $object, string $property): mixed
    {
        $p = $this->reflector->getProperty($property);
        $p->setAccessible(true);

        return $p->getValue($object);
    }
}
